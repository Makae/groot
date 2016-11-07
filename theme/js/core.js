var search = {
  query_input : null,
  cat_input : null,
  init : function() {
    var self = this;
    this.query_input = $('#search input[name="query"]');
    this.cat_input = $('#search select[name="cat"]');

    var updateAutocomplete = (function() {
      var timeout;
      var min_length = 2;
      var threshold = 150;
      return function() {
        if(timeout)
          window.clearTimeout(timeout);

        if(self.query_input.val().length < min_length)
          return;

        timeout = window.setTimeout(function() {
          self.loadAutocomplete();
        }, threshold);
      };
    })();

    this.query_input.blur(function() {
      self.hideAutocomplete();
    }).keyup(updateAutocomplete)
      .focus(updateAutocomplete);

    this.cat_input.change(function() {
      self.hideAutocomplete();
    });

  },

  hideAutocomplete : function() {
    $('body #ac-dropdown').remove();
  },

  loadAutocomplete : function() {
    var self = this;
    var query = this.query_input.val();
    var category = this.cat_input.val();
    $.ajax({
      type : "POST",
      url : "?view=search&ajax=1&ajax_fn=autocomplete",
      data : {query:query, cat: category},
      success : function(e) {
        var suggestions = [];
        for(var i in e.books)
          suggestions.push(e.books[i].title);
        self.showSuggestions(suggestions, query);
      }
    });
  },

  showSuggestions : function(entries, query) {
    var self = this;
    $ddn = $('<ul id="ac-dropdown" class="ac-dropdown" /></ul>');
    for(var i in entries) {
      var entry = entries[i];
      $ddn.append($('<li>' + entry + '</li>'))
    }
    var left = this.query_input.offset().left;
    var top = this.query_input.offset().top + this.query_input.innerHeight();

    $ddn.attr('data-value', query);

    $ddn.css({
      position: 'absolute',
      left : left,
      top : top
    }).mouseleave(function() {
      self.setSearchInput($(this).attr('data-value'));
    }).find('li').click(function() {
      self.setSearchInput($(this).html());
      self.hideAutocomplete();
    }).hover(function() {
      self.setSearchInput($(this).html());
    });
    self.hideAutocomplete();
    $('body').append($ddn);
  },

  setSearchInput : function(str) {
    var str = str.replace(/(<([^>]+)>)/ig, '');
    this.query_input.val(str);
  }
};

var wiki = {
  handler : null,
  offsetX : 15,
  offsetY : 15,
  lastX : null,
  lastY : null,

  init : function() {
    var self = this;
    self.registerHandler();
    $("*[data-wiki]").each(function() {
      $(this).mouseover(function(e) {
        self.lastX = e.pageX + self.offsetX;
        self.lastY = e.pageY + self.offsetY;
        $(this).addClass('loading');
        self.active = true;
        self.loadWiki($(this));
      }).mouseleave(function(e) {
        self.lastX = e.pageX + self.offsetX;
        self.lastY = e.pageY + self.offsetY;
        $(this).removeClass('loading');
        self.hideWiki();
        self.active = false;
      });
    });
  },

  loadWiki : function($element) {
    var query = $element.attr('data-wiki');
    var self = this;
    $.ajax({
      type : "POST",
      url : "?view=ajax&ajax=1&ajax_fn=wiki",
      data : {query:query},
      success : function(e) {
        if(self.active == false)
          return;
        var html = '<h4>' + e.query + '</h4><p>' + e.wiki + '</p>';
        self.showWiki(html);
        $element.removeClass('loading');
      }
    });
  },

  showWiki : function(html) {
    if($('.wiki').length == 0) {
      $wiki = $('<div class="wiki"></div>').css({
        'top' : this.lastY + 'px',
        'left' : this.lastX + 'px'
      }).appendTo('body');
    } else {
      $wiki = $('.wiki').css({
        'top' : this.lastY + 'px',
        'left' : this.lastX + 'px'
      });
    }

    $wiki.html(html);

  },

  hideWiki : function() {
    $('.wiki').remove();
  },

  registerHandler : function() {
    var self = this;
    this.handler = function(e) {
      var top = e.pageY + self.offsetY;
      var left = e.pageX + self.offsetX;
      $('.wiki').css({
        'top': top + 'px',
        'left': left + 'px',
      })
    };
    $('body').mousemove(this.handler);
  },

  unregisterHandler : function() {
    $('body').off('mousemove', this.handler);
  }

};

var showcase = {
  init : function() {
    var self = this;
    $(".showcase-wrapper[data-config]").each(function() {
      var config = JSON.parse(decodeURIComponent($(this).attr('data-config')));
      config.instance = $(this);

      $(this).find(".nav.prev").click(function() {
        if(config.page == 0 || config.loading)
          return;

        config.loading = true;
        self.getPage(config.request, config.page-1, config.size, function(result){
          config.loading = false;
          self.pageLoaded(result, config);
          config.page--;
          self.updateNavigation(config);
        });
      });

      $(this).find(".nav.next").click(function() {
        if(config.loading == true)
          return;

        config.loading = true;
        self.getPage(config.request, config.page+1, config.size, function(result){
          config.loading = false;
          self.pageLoaded(result, config);
          config.page++;
          self.updateNavigation(config);
        });
      });
    });
  },

  getPage : function(request, page, size, callback) {
    var url = request.replace('{%page%}', page).replace('{%size%}', size);
    $.ajax({
      type : "POST",
      url : url,
      success : function(e) {
        callback(e)
      }
    });
  },

  pageLoaded : function(e, config) {
    if(e.html == null)
      return;
    this.updateContent(e.html, config);
  },

  updateContent : function(content, config) {
    var new_showcase = $(content).find(".showcase");
    config.instance.find('.showcase').html(new_showcase);
  },

  updateNavigation : function(config) {
    if(config.page == 0)
      config.instance.find(".nav.prev").addClass('hidden');
    else
      config.instance.find(".nav.prev").removeClass('hidden');
  }

}

var core = {
  init : function() {
    search.init();
    wiki.init();
    showcase.init();
  }
};

function orderConfirmation() {
  //Are you really sure you want to buy all the previous Stuff? Last chance to abort!
  var orderConfirmationInfo = "Sind Sie sicher, dass Sie alle Angaben korrekt erfasst haben?"
  result = window.confirm(orderConfirmationInfo);
    if (result){
      document.getElementById("realSubmitButton").click();
      //return true;
    }else{
      //return false;
    }

  };



  //are the important user fields correctly filled?
  function validateUser() {
    var x = document.forms["formUser"]["email"].value;
    var atpos = x.indexOf("@");
    var dotpos = x.lastIndexOf(".");
    var user_name = document.forms["formUser"]["user_name"].value;
    var first_name = document.forms["formUser"]["first_name"].value;
    var last_name = document.forms["formUser"]["last_name"].value;
    var password = document.forms["formUser"]["password"].value;
    var lang = document.forms["formUser"]["lang"].value;
    if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=x.length) {
      //Not a valid e-mail address
        alert("Keine korrekte E-Mail Adresse");
        return false;
    }else if(!user_name){
        //Please enter a Loginname
        alert("Bitte einen Loginname angeben");
        return false;
    }else if(!first_name){
        //Please enter a first name
        alert("Bitte einen Vorname angeben");
        return false;
    }else if(!last_name){
        //Please enter a last name
        alert("Bitte einen Nachname angeben");
        return false;
    }else if(!password){
        //Please enter a password
        alert("Bitte ein Passwort angeben");
        return false;
    }else if(!lang){
      //Please enter a language
        alert("Bitte eine Sprache angeben");
        return false;
    }else{
      //alert("User successfully created!");
      alert("Benutzer wurde erfolgreich angelegt!");
      document.getElementById("createUser").click();
    }
  }

  //are the important book fields correctly filled?
  function validateBook() {
    var name = document.forms["formBook"]["name"].value;
    var isbn = document.forms["formBook"]["isbn"].value;
    var title = document.forms["formBook"]["title"].value;
    var author = document.forms["formBook"]["author"].value;
    var price = document.forms["formBook"]["price"].value;
    var year_of_publication = document.forms["formBook"]["year_of_publication"].value;
    if (!name) {
      //Please enter a name
        alert("Bitte einen Namen angeben");
        return false;
    }else if(!isbn){
      //alert("Please enter a isbn");
        alert("Bitte eine ISBN Nr angeben");
        return false;
    }else if(isbn.length < 8){
      //isbn is too short!
        alert("Die ISBN Nummer muss mindestens 8 Zeichen lang sein!");
        return false;
    }else if(!Number.isInteger(parseInt(isbn))){
      //isbn has to be a number
        alert("Die ISBN muss eine Zahl sein!");
        return false;
    }else if(!title){
      //Please enter a first title
        alert("Bitte ein Titel angeben");
        return false;
    }else if(!author){
      //Please enter a author
        alert("Bitte einen Autor angeben");
        return false;
    }else if(!price){
      //Please enter a price
        alert("Bitte einen Preis angeben");
        return false;
    }else if(year_of_publication.length != 4){
      //The year has to be 4 digits long
        alert("Das Jahr muss aus 4 Zahlen bestehen");
        return false;
    }else if(!Number.isInteger(parseInt(year_of_publication))){
      //The year of publication has to be a number
        alert("Das Erscheinungsjahr muss eine Zahl sein");
        return false;
    }else{
      //Book successfully created!
      alert("Buch wurde erfolgreich angelegt!");
      document.getElementById("createBook").click();
    }
  }
$(document).ready(function() {
  core.init();
});