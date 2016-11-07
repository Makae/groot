<?php
  /*
  *
  *header part of the site content
  * contains the search, genre-selectbox, language selector and the login window
  *
  */

  class GrootHeaderViewlet implements IViewlet {

    public static function name() {
      return 'header';
    }

    public function process($config) {
      // Here comes the processing of the field-parameters
    }


    //checks if the user and password is correct
    public function login($user=false,$password=false){
        $user = isset($_POST['Loginname']) ? $_POST['Loginname'] : $user ;
        $password = isset($_POST['Password']) ? $_POST['Password'] : $password ;
        return UserHandler::instance()->login($user, $password);
    }



    //checks if the user and password is correct, testfunction
    public function loginMessage($user=false,$password=false){
        if($user OR $password){

          $user = isset($_POST['Loginname']) ? $_POST['Loginname'] : $user ;
          $password = isset($_POST['Password']) ? $_POST['Password'] : $password ;
          if(UserHandler::instance()->login($user, $password)){
            return "User is logged in";
          }else{
            return "User is NOT logged in";
          }
        }else{

        }


    }

    //log out user, if logout-Button is pressed and logut submitted
    public function logout(){
      if(isset($_POST['Logout'])){
        return UserHandler::instance()->logout();
      }

    }




    //creates the Menu from a Navi Array
  public function makeMenu(){
        $html = '<div class="login-mask ">
                    <form action="" method="POST">
                      <div class="mask"></div>
                      <div class="buttons"> </div>
                    </form>
                  </div>';

    return $html;
}

  public function render() {
      // Here comes the rendering process
      $this->logout();
      $this->login();
      return $this->makeMenu();


  }

  public function ajaxCall() {

  }

}
?>