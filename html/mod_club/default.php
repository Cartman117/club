<?php
/**
 * @package		Joomla.Site
 * @subpackage	Templates.club
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<script language="javascript" src="mod_club_form.js"></script>
<?php
	include("./mod_club_class_form.php");
	$formulaire =  new Form(NULL, "post");
	$formulaire->addInput("nom", "text", "Nom : ", NULL, 25, TRUE);
	$formulaire->addInput("prenom", "text", "Prénom : ", NULL, 25, TRUE);
	
	$formulaire->closeForm("register", "S'enregistrer");
?>
<input type="number" name="jour" maxlength="2"/>
<select name="mois">
<option value="1">Janvier</option>
<option value="2">Février</option>
<option value="3">Mars</option>
<option value="4">Avril</option>
<option value="5">Mai</option>
<option value="6">Juin</option>
<option value="7">Juillet</option>
<option value="8">Août</option>
<option value="9">Septembre</option>
<option value="10">Octobre</option>
<option value="11">Novembre</option>
<option value="12">Décembre</option>
</select>
<input type="number" name="annee" maxlength="4"/>
<input type="submit" value="S'enregistrer" name="register"/>
</form>
