// JavaScript Document
function afficheFormulaireMineur()
{
	document.getElementById("majeur").style.display = "none";
	document.getElementById("mineur").style.display = "block";
}

function afficheFormulaireMajeur()
{
	document.getElementById("mineur").style.display = "none";
	document.getElementById("majeur").style.display = "block";
}

function afficheSuiteFormTournoi()
{
	document.getElementById("majeur").style.display = "none";
	document.getElementById("mineur").style.display = "block";
}

function cacheSuiteFormTournoi()
{
	document.getElementById("mineur").style.display = "none";
	document.getElementById("majeur").style.display = "block";
}