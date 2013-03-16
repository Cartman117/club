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
	document.getElementById("formulaireTournament").style.display = "block";
}

function cacheSuiteFormTournoi()
{
	document.getElementById("formulaireTournament").style.display = "none";
}

function afficheFormulaireAjoutTournoi()
{
	document.getElementById("ajoutTournoi").style.display = "block";
	document.getElementById("nouveauTournoi").style.display = "none";
}

function afficheFormulaireNouveauTournoi()
{
	document.getElementById("nouveauTournoi").style.display = "block";
	document.getElementById("ajoutTournoi").style.display = "none";
}