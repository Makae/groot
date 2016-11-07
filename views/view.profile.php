<?php
  class GrootProfileView implements IView {

    public function name() {
      return 'profile';
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

    public function render() {
      // Here comes the rendering process
return '
<div id="content">
          <h1>Benutzerprofil</h1>
      <p>
      <div class="profil">
        <div class="picture"><img  src="theme/images/user.png" height="80" width="80" />
        </div>
        <div class="settings">
          <p><div class="label1 column1" >Name:</div><input class="column1 input1" value="Hansruedi" name="prename" /></p>
          <p><div class="label1 column1" >Vorname:</div><input class="column1 input1" value="Geissler" name="12" /></p>
          <p><div class="label1 column1" >Strasse:</div><input class="column1 input1" value="Steinisbruggweg 23" name="231" /></p>
          <p><div class="label1 column1" >Ort:</div><input class="column1 input1" value="Hilterfingen" name="142" /></p>
          <p><div class="label1 column1" >PLZ:</div><input class="column1 input1" value="2401" name="521" /></p>
          <p><div class="label1 column1" >Land:</div><select class="column1 input1" name="country" size="1">
      <option>Schweiz</option>
      <option>Deutschland</option>
      <option>England</option>
      <option>Frankreich</option>
      <option>Spanien</option>
    </select></p>
          <p><div class="label1 column1" >Sprache:</div><select class="column1 input1" name="language" size="1">
      <option>Deutsch</option>
      <option>B&auml;rnd&uuml;sch</option>
      <option>English</option>
      <option>Nina Hagen</option>
      <option>Marianne Rosenberg</option>
    </select></p>
          <p><div class="label1 column1" >Newsletter:</div><input class="column1 input1" type="checkbox" name="checkbox" value="value"></p>
          <p><div class="label1 column1" >E-Mail:</div><input class="column1 input1" value="heimri@flexinius.com" name="421" /></p>
        </div>
      </div>
        </div>
';
  #  return =  Here will be the profile render soon ... ";
    }

    public function ajaxCall() {
      // we will return the value as json encoded content
      return json_encode('asdf');
    }

  }
?>