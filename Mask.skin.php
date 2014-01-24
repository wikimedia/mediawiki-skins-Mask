<?php

/**
 * Mask skin stuff.
 *
 * @file
 * @ingroup Skins
 * @author Calimonius the Estrange
 * @author Jack Phoenix
 * @authors Whoever wrote monobook
 * @date 2013
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinMask extends SkinTemplate {
	var $skinname = 'mask', $stylename = 'mask',
		$template = 'MaskTemplate', $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		global $wgFontCSSLocation;
		parent::setupSkinUserCss( $out );

		# Because of weird font licensing issues or something
		if ( isset( $wgFontCSSLocation ) ) {
			$out->addStyle( $wgFontCSSLocation, 'screen' );
		}
		# Add css
		$out->addStyle( 'mask/main.css', 'screen' );
	}
}

/**
 * Main skin class
 * @ingroup Skins
 */
class MaskTemplate extends BaseTemplate {

	/**
	 * Template filter callback for Mask skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgHostLink;

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
		?><div id="globalWrapper">
		<div id="top-container">
			<div id="nav-container">
				<div id="top-coin" class="portlet" role="banner">
					<?php
					echo Html::element( 'a', array(
					'href' => $this->data['nav_urls']['mainpage']['href'],
					'style' => "background-image: url({$this->data['logopath']});" )
					+ Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) );
					?>
				</div>
			<?php
			$this->renderPortals( $this->data['sidebar'] );
			?>
			</div>
		</div>
	<div id="content-container">
		<div id="paper-top">
		</div>
		<div id="border-top">
		</div>
		<div id="content" class="mw-body-primary" role="main">
			<a id="top"></a>
			<?php if ( $this->data['sitenotice'] ) { ?><div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div><?php } ?>

			<div id="bodyContent" class="mw-body">
				<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
				<?php if ( $this->data['undelete'] ) { ?>
					<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<?php } ?><?php if ( $this->data['newtalk'] ) { ?>
					<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
				<?php } ?>
				<div id="jump-to-nav" class="mw-jump"><?php $this->msg( 'jumpto' ) ?> <a href="#nav-container"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' ) ?><a href="#searchInput"><?php $this->msg( 'jumptosearch' ) ?></a></div>

			<!-- start content -->
				<?php $this->html( 'bodytext' ) ?>
				<?php if ( $this->data['catlinks'] ) { $this->html( 'catlinks' ); } ?>
			<!-- end content -->
				<?php if ( $this->data['dataAfterContent'] ) { $this->html( 'dataAfterContent' ); } ?>
				<div class="visualClear"></div>
			</div>
		</div>

		<div id="tools-bottom"<?php $this->html( 'userlangattributes' ) ?>>
			<div id="site-tools">
				<div class="portlet" id="p-personal" role="navigation">
					<div class="pBody">
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php foreach ( $this->getPersonalTools() as $key => $item ) { ?>
								<?php echo $this->makeListItem( $key, $item ); ?>
							<?php } ?>
						</ul>
					</div>
				</div>
				<?php
				$this->searchBox();
				?>
			</div>
			<div id="page-tools">
				<span id="page-title" lang="<?php
					$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
					$this->text( 'pageLanguage' );
					?>" dir="auto"><?php $this->html( 'title' ) ?>
				</span>:
				<?php  $this->cactions(); ?>
			</div>
			<div class="visualClear"></div>
		</div>
		<div id="paper-bottom">
		</div>
		<div id="border-bottom">
		</div>
	</div>
	<div id="bottom-container">
		<div id="bottom-nav-container">
			<div id="bottom-coin" role="banner">
				<?php
				if ( isset( $wgHostLink ) ) {
					$url = $wgHostLink;
				} else {
					$title = Title::newFromText( wfMessage( 'aboutpage' )->inContentLanguage()->parse() );
					$url = $title->getFullURL();
				}
				echo Html::element( 'a', array('href' => $url ) );
				?>
			</div>
		</div>
	</div>
	<div class="visualClear"></div>

</div>
<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/

	/**
	 * @param $sidebar array
	 */
	protected function renderPortals( $sidebar ) {

		foreach ( $sidebar as $boxName => $content ) {
			if ( $content === false ) {
				continue;
			}
			$this->customBox( $boxName, $content );
		}
	}

	function searchBox() {
?>
	<div id="p-search" class="portlet" role="search">
		<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
		<div id="simpleSearch">
			<?php echo $this->makeSearchInput( array( 'id' => 'searchInput', 'type' => 'text' ) ); ?>
			<?php echo $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) ); ?>
			<?php # echo $this->makeSearchButton( 'fulltext', array( 'id' => 'mw-searchButton', 'class' => 'searchButton' ) ); ?>
			<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
		</div>
	</form>
	</div>
<?php
	}

	/**
	 * Prints the cactions bar.
	 * Shared between Mask and Modern
	 */
	function cactions() {
?>
	<div id="p-cactions" class="portlet" role="navigation">
		<div class="pBody">
			<ul><?php
				foreach ( $this->data['content_actions'] as $key => $tab ) {
					echo '
				' . $this->makeListItem( $key, $tab );
				}

				// what links here
				if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
					$title = SpecialPage::getTitleFor( 'Whatlinkshere', $this->getSkin()->getTitle() );
					$link = Linker::link( $title, wfMessage( 'whatlinkshere-short' )->text() ); ?>
					<li id="ca-links"><?php echo $link; ?></li>
					<?php
				}
				// purge
				$title = $this->getSkin()->getTitle();
				$link = Linker::link( $title, wfMessage( 'refresh' )->text(), array(), array( 'action' => 'purge' ) ); ?>
				<li id="ca-purge"><?php echo $link; ?></li>
			</ul>
		</div>
	</div>
<?php
	}

	/*************************************************************************************************/
	/**
	 * @param $bar string
	 * @param $cont array|string
	 */
	function customBox( $bar, $cont ) {
		$portletAttribs = array( 'class' => 'generated-sidebar portlet', 'id' => Sanitizer::escapeId( "p-$bar" ), 'role' => 'navigation' );
		$tooltip = Linker::titleAttrib( "p-$bar" );
		if ( $tooltip !== false ) {
			$portletAttribs['title'] = $tooltip;
		}
		echo '	' . Html::openElement( 'div', $portletAttribs );
		$msgObj = wfMessage( $bar );
?>

		<h3><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $bar ); ?></h3>
		<div class='pBody'>
<?php   if ( is_array( $cont ) ) { ?>
			<ul>
<?php 			foreach ( $cont as $key => $val ) { ?>
				<?php echo $this->makeListItem( $key, $val ); ?>

<?php			} ?>
			</ul>
<?php   } else {
			# allow raw HTML block to be defined by extensions
			print $cont;
		}
?>
		</div>
	</div>
<?php
	}
} // end of class
