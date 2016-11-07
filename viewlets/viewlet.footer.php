<?php
  class GrootFooterViewlet implements IViewlet {

    public static function name() {
      return 'footer';
    }

    public function process($config) {
      // Here comes the processing of the field-parameters
    }

    public function render() {
      // Here comes the rendering process

  //classes
   $footerClass = "impressum stdanimation1_2";

 //array mit link, icon, label
  // mit i übersetzen
  $footerArray[] = array("link" => "index.php?view=home", "icon" => "icon_house_alt", "label" => "Impressum" );
  $footerArray[] = array("link" => "index.php?view=home", "icon" => "icon_tag", "label" => "Contact" );
  $footerArray[] = array("link" => "index.php?view=home", "icon" => "icon_profile", "label" => "Shop version" );
  $footerArray[] = array("link" => "index.php?view=home", "icon" => "icon_gift", "label" => "Support" );

  $footerList = "";

  //create a list item for every array found
  $footerList = "";
  foreach($footerArray as $footerpoint){
    //do translation
    $footerpoint["label"] = i($footerpoint["label"]);

    $footerList .= '<li class="'.$footerClass.'" ><a href="'.$footerpoint["link"].'">'.$footerpoint["label"].'</a></li>';
  }

    return '<ul>' . $footerList .'</ul>';

/*
      return '<div class="impressum stdanimation1_2">Impressum</div>
      <div class="impressum stdanimation1_2">Kontakt</div>
      <div class="impressum stdanimation1_2">Shopversion</div>
      <div class="impressum stdanimation1_2">&Uuml;ber uns</div>
      <div class="impressum stdanimation1_2">Support Hotline</div>';
      */
    }


    public function ajaxCall() {

    }

  }
?>