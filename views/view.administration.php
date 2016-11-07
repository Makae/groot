<?php

/**
* Maske zum Erstellen von Büchern und zum Anlegen von Benutzern
* ist nur für Benutzer mit Adminrechten zugänglich
* die Forms werden mit javascript geprüft
*@author TSCM
*/
  class GrootAdministrationView implements IView {

    public function name() {
      return 'administration';
    }

    public function viewletMainMenu() {
      return null;
    }

    public function viewletNavi() {
      return array();
    }

    public function viewletFooter() {
      return null;
    }

    public function process() {
      // Here comes the processing of the field-parameters
    }

    //renders the page
    public function render() {

      //call functions to catch form executes
      $this->createBook();
      $this->createUser();
      


      //get the an array for all the labels

      //check admin rights
    $isAdmin = Utilities::checkIsAdmin();
     if(!$isAdmin){
         return '<h1>'.i("no_rights_msg").'</h1>';
         //";header( 'Location: http://localhost/src/index.php?view=home' ) ;
      }

    //----
    //---- create book
    //----
    //Product array
    $products = array();
    //get the product list
    $products = BookModel::findList(null,null);
    //create an arraylist
    //only need one array to set up the product list with the right names
    $i = 0;
    foreach($products as $book){
       if($i > 0){
          continue;
        }
      $inputBook = Utilities::buildInput($book);
      $i++;
    }

    //----
    //---- create user
    //----

    //Product array
    $users = array();
    //get the product list
    $users = UserModel::findList(null,null);
    //create an arraylist
    //only need one array to set up the product list with the right names
    $i = 0;
    foreach($users as $user){
       if($i > 0){
          continue;
        }
      $inputUser = Utilities::buildInput($user);
      $i++;
    }

  // Here comes the rendering process
return '
    <div id="content">
      <h1>'.i("Administration").'</h1>
      <br />
      <hr>
      <br />

      <h1>'.i("Book").'</h1>
       <form name="formBook" action="index.php?view=administration" method="post" >
      <div class="profil">
        <div class="picture"><img  src="theme/images/book_1.png" height="80" width="80" />
        </div>

        <div class="settings" >
          '.$inputBook.'
          <input class="button button-primary hidden" type="submit" id="createBook" name="createBook"  value="'.i("Add Book").'" />
          <input class="button button-primary" type="button" onclick="validateBook()" value="'.i("Add Book").'" />
        </div>
      </div>
   
      </form>
    </div>

<br />
<hr>
<br />

    <div id="content">

      <h1>'.i("Benutzer").'</h1>
       <form name="formUser" action="index.php?view=administration" method="post">
      <div class="profil">
        <div class="picture"><img  src="theme/images/user.png" height="80" width="80" />
        </div>

        <div class="settings" >
          '.$inputUser.'
          <input class="button button-primary hidden" type="submit" id="createUser" name="createUser"  value="'.i("Add user").'" />
          <input class="button button-primary" type="button" onclick="validateUser()" value="'.i("Add user").'" />
        </div>
      </div>
   
      </form>
    </div>

<br />
<hr>
<br />
';

}

  public function ajaxCall() {
    // we will return the value as json encoded content
    return json_encode('asdf');
  }

/**
* Create a Book in our mysql DB works with form and $_Post
* @author TSCM
*/
  public function createBook(){
    if(isset($_POST['createBook'])){
      unset($_POST['createBook']);//delete the content of the createBook button
      BookModel::create($_POST); //create book
    }
  }

/**
* Create a user in our mysql DB works with form and $_Post
* @author TSCM
*/
  public function createUser(){
    if(isset($_POST['createUser'])){
      unset($_POST['createUser']);//delete the content of the createBook button
      $_POST['password'] = Utilities::hash($_POST['password'], USER_SALT);
      UserModel::create($_POST); //create book

    }
  }

  }
?>