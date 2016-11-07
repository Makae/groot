<?php
 //Navigation part of the html page
 //render() shows a navigation list
  class GrootNaviViewlet implements IViewlet {

    public static function name() {
      return 'navi';
    }

    public function process($config) {
      // Here comes the processing of the field-parameters

    }

//creates the Menu from a Navi Array
public function makeMenu(){

  //Label & class definition
  $idDiv = "sub-menu";
  $classUl = "menu";
  $classLi = "stdanimation1_2";
  $classSpan = "stdanimation1_2";
  $classA = "stdanimation1_4";

  //array mit link, icon, label
  $naviArray[] = array("link" => "index.php?view=home", "icon" => "icon_house_alt", "label" => "Home" );
  $naviArray[] = array("link" => "index.php?view=profile", "icon" => "icon_profile", "label" => "Profile" );
  $naviArray[] = array("link" => "index.php?view=genres", "icon" => "icon_tag", "label" => "Genres" );
  $naviArray[] = array("link" => "index.php?view=shoppingcart", "icon" => "icon_cart", "label" => "Shopping Cart" );
  $naviArray[] = array("link" => "index.php?view=home", "icon" => "icon_gift", "label" => "Wishlist" );
  $naviArray[] = array("link" => "index.php?view=home", "icon" => "icon_grid-2x2", "label" => "Hotlist" );


  //only admin user can access this navi links
  $isAdmin = Utilities::checkIsAdmin();
  if($isAdmin){
    $naviArray[] = array("type" => "seperator", "cls" => 'seperator-top' );
    $naviArray[] = array("link" => "index.php?view=administration", "icon" => "icon_profile", "label" => "Administration" );
  }

  $linkList = "";

  //create a list item for every array found
  foreach($naviArray as $navipoint){

    if(isset($navipoint['type']) && $navipoint['type'] == 'seperator') {
      $linkList .= '<li class="seperator ' . $navipoint['cls'] . '"></li>';
      continue;
    }
    $li_cls = isset($navipoint['cls']) ? ' ' . $navipoint["cls"] : '';
    $navipoint["label"] = i($navipoint["label"]);

    $cls = $classSpan . ' ' . $navipoint["icon"];
    $linkList .= '
            <li class="'.$classLi . $li_cls . '">

              <span class="' . $cls . '"></span>
              <a class="'.$classSpan.'" href="'.$navipoint["link"].'">'.$navipoint["label"].'</a>
            </li>
    ';
  }

  $html = "";
 $html = '<div id="'.$idDiv.'">
          <ul class="'.$classUl.'">
            '.$linkList.'
          </ul>
        </div>';

        return $html;
}

    public function render() {

      // Here comes the rendering process


        return $this->makeMenu();


    }

    public function ajaxCall() {

    }

  }
?>