<?php
  /**
   * UserHandler manages the login and logout process of the user
   * @author: M. Käser
   * @date:   25.10.2014
   */
  class UserHandler {
    const SESSION_KEY = 'user_id';
    const ERROR_EXISTS = 1;

    private static $instance;
    // The salt argument is used in the md5-hashing algorithm
    private static $salt = USER_SALT;
    private $user;

    /**
     * Singleton constructor. logs the user in automatically,
     * if he has its user id in the $_SESSION["user_id"] variable
     */
    private function __construct() {
      if(!$this->loggedin()) {
        $this->user = null;
        return;
      }
      $id = $_SESSION[UserHandler::SESSION_KEY];
      $this->user = new UserModel($id);
    }


    /**
     * Returns the UserHandler instance
     *
     * @return UserHandler $instance
     */
    public static function instance() {
      if(static::$instance != null)
        return static::$instance;
      return static::$instance = new UserHandler();
    }

    /**
     * Returns the currently logged in user
     *
     * @return UserModel $user
     */
    public function user() {
      return $this->user;
    }


    /**
     * Logs the user in, is not necessary if the user_id key
     * is already in the session
     *
     * @param string $user_name
     * @param string $password - plaintext password
     *
     * @return bool $success
     */
    public function login($user_name, $password) {
      $user = UserModel::findFirst(array(
        'user_name' => $user_name,
        'password' => Utilities::hash($password, static::$salt)
      ));

      if($user == null)
        return false;

      $this->_login($user);

      return true;
    }

    /**
     * logout the user by removing the user_id session variable
     */
    public function logout() {
      unset($_SESSION[UserHandler::SESSION_KEY]);
      $this->user = null;
    }

    /**
     * Checks if the user is logged in
     *
     * @return bool $loggedin
     */
    public function loggedin() {
      return isset($_SESSION[UserHandler::SESSION_KEY]);
    }

    /**
     *  Register the user, the user is automatically logged in after call
     *
     *  @todo Confirmation mail on registration
     *  @param string $user_name
     *  @param string $password - plaintext password
     *  @param string $first_name
     *  @param string $last_name
     *  @return UserModel $user
     *
     */
    public function register($user_name, $password, $first_name, $last_name) {

      if(!is_null(UserModel::findFirst(array('user_name' => $user_name))))
        return UserHandler::ERROR_EXISTS;

      $user = UserModel::create(array(
        'user_name' => $user_name,
        'password' => Utilities::hash($password, static::$salt),
        'first_name' => $first_name,
        'last_name' => $last_name,
        'lang' => I18N::lang()
      ));

      $this->_login($user);

      return $user;
    }

    /**
     * Sets the user property and session variable
     *
     * @param UserModel $user
     *
     */
    private function _login($user) {
      $this->user = $user;
      $_SESSION[UserHandler::SESSION_KEY] = $user->id();
    }

  }
?>