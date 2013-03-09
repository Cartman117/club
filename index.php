<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
JHtml::_('behavior.framework', true);

/* Récupération des paramètres */
$logo = $this->params->get('logo');
$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params;
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<!-- The following JDOC Head tag loads all the header and meta information from your site config and content. -->
		<jdoc:include type="head" />
		<!-- Appel des différents CSS ncessaires -->
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/position.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/menu.css" rel="stylesheet" type="text/css" />
	</head>
	
	<body>
    	<!-- Barre de login et d'inscription du site -->
		<div id="login">
		<?php	if($this->countModules('club-login')) : ?>
            <div id="account">
            <jdoc:include type="modules" name="club-login" style="none" />
            </div>
        <?php endif; ?>
		</div>
		<div id="body">
			<div id="all">
            	<!--Header du site -->
				<div id="header">
					<div id="logoHeader">
						<div id="logo">
						<?php	if ($logo): ?>
						<img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>"  alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
						<?php 	endif;
								if (!$logo ):
									echo htmlspecialchars($templateparams->get('sitetitle'));
								endif;?>
						</div>
					</div>
					<?php	if($this->countModules('club-search')) : ?>
					<div id="joomlaSearch">
						<jdoc:include type="modules" name="club-search" style="none" />
					</div>
					<?php endif; ?>
				</div>
                <!-- Fin du Header -->
                
                <!-- Menu du site -->
				<?php if($this->countModules('club-topmenu')) : ?>
				<div id="menu">	
                    <jdoc:include type="modules" name="club-topmenu" style="container" />
				</div>
				<?php endif; ?>
                <!-- Fin du menu -->
                
                <!-- Menu pour les équipes -->
                <?php if($this->countModules('club-teammenu')) : ?>
                <div id="menuEquipes">
					<jdoc:include type="modules" name="club-teammenu" style="container" />
                </div>
				<?php endif; ?>
                <!-- Fin du menu -->
                
                <!-- Conteneur global du site -->
				<div id="globalContainer">
                
                	<!-- Affichage des résultats de recherche, de connexion et de certains articles -->
                	<div id="center">
                    	<br/>
                        <jdoc:include type="component" />
                    </div>
                    
                    <!-- Pour l'affichage de la présentation sur la page d'accueil du site -->
                    <?php if($this->countModules('club-accueil-presentation')) : ?>
                    <div id="accueilPresentation">
                        <jdoc:include type="modules" name="club-accueil-presentation" style="container" />
                    </div>
                    <?php endif; ?>  
            
                    <!-- Pour l'affichage des compétitions sur la page d'accueil du site -->
                    <?php if($this->countModules('club-accueil-competitions')) : ?>
                    <div id="accueilCompetitions">
                        <jdoc:include type="modules" name="club-accueil-competitions" style="container" />
                    </div>
                    <?php endif; ?>
                        
                    <!-- Pour l'affichage des nouveautés sur la page d'accueil du site -->
                    <?php if($this->countModules('club-accueil-nouveautes')) : ?>
                    <div id="accueilNouveautes">
                        <jdoc:include type="modules" name="club-accueil-nouveautes" style="container" />
                    </div>
                    <?php endif; ?>
                
                    <!-- Pour l'affichage des articles sur les autres pages (pas encore fini) -->
                    <?php if($this->countModules('club-article')) : ?>
                    <div id="article">
                        <jdoc:include type="modules" name="club-article" style="container" />
                    </div>
                    <?php endif; ?>
                    
				</div>
                <!-- Fin du Conteneur global -->
        	</div>
		</div>
        <!-- Footer du site -->
        <div id="footer">
            <div id="logos">
            	<a href="http://www.fftri.com/">
              		<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/logo-fftri.bmp" alt="Fédération Française de Triathlon" height="62" width="70"/>
            	</a>
            </div>
            <!-- Joomla-->
        	<div id="joomla">
            	Animé par <a href="http://www.joomla.org">Joomla!&#174;</a>
            </div>
            <!-- Fin de Joomla -->
        </div>
        <!-- Fin du Footer -->
	</body>
</html>
