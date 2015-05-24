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
	public $skinname = 'mask', $stylename = 'mask',
		$template = 'MaskTemplate', $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		global $wgFontCSSLocation;
		parent::setupSkinUserCss( $out );

		# Add css
		$out->addModuleStyles( array (
			'mediawiki.skinning.content.externallinks',
			'skins.mask'
		) );
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
					echo Html::element(
						'a',
						array(
							'href' => $this->data['nav_urls']['mainpage']['href'],
							'style' => "background-image:  url(" . $this->getLogoURL() . ");"
						)
						+ Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
					);
					?>
				</div>
				<div id="menu-left">
				<?php
					$this->renderNavigation( 'mask-menu-left', 'menu-left' );
				?>
				</div>
				<div id="menu-right">
				<?php
					$this->renderNavigation( 'mask-menu-right', 'menu-right' );
				?>
				</div>
			</div>
		</div>
	<div id="content-container">
		<div id="paper-top">
		</div>
		<div id="border-top">
		</div>
		<div id="content" class="mw-body-primary" role="main">
			<a id="top"></a>
			<?php if ( $this->data['sitenotice'] ) { ?><div id="siteNotice">
				<?php $this->html( 'sitenotice' ) ?>
			</div><?php } ?>

			<div id="bodyContent" class="mw-body">
				<div id="siteSub">
					<?php $this->msg( 'tagline' ) ?>
				</div>
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>>
					<?php $this->html( 'subtitle' ) ?>
				</div>
				<?php
				if ( $this->data['undelete'] ) { ?>
					<div id="contentSub2">
						<?php $this->html( 'undelete' ) ?>
					</div>
					<?php
				}
				if ( $this->data['newtalk'] ) { ?>
					<div class="usermessage">
						<?php $this->html( 'newtalk' ) ?>
					</div>
				<?php } ?>
				<div id="jump-to-nav" class="mw-jump">
					<?php $this->msg( 'jumpto' ) ?>
					<a href="#nav-container"><?php $this->msg( 'jumptonavigation' ) ?></a>
					<?php $this->msg( 'comma-separator' ) ?>
					<a href="#searchInput"><?php $this->msg( 'jumptosearch' ) ?></a>
				</div>

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
							<?php
							foreach ( $this->getPersonalTools() as $key => $item ) {
								echo $this->makeListItem( $key, $item );
							}
							?>
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
					?>"><?php $this->html( 'title' ) ?>
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
			<div id="bottom-nav">
				<?php
				$this->renderPortals( $this->data['sidebar'] );
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
	 * Print arbitrary block of navigation
	 * @param $linksMessage
	 * @param $blockId
	 * Message parsing is limited to first 4 lines only for this skin.
	 */
	private function renderNavigation( $linksMessage, $blockId ) {
		$message = trim(  wfMessage( $linksMessage )->text() );
		$lines = array_slice( explode( "\n", $message ), 0, 4 );
		$links = array();
		foreach ( $lines as $line ) {
			# ignore empty lines
			if ( strlen( $line ) == 0 ) {
				continue;
			}
			$links[] = $this->parseItem( $line );
		}

		$this->customBox( $blockId, $links );
	}

	/**
	 * Extract the link text and destination (href) from a MediaWiki message
	 * and return them as an array.
	 */
	private function parseItem( $line ) {
		$line_temp = explode( '|', trim( $line, '* ' ), 2 );
		if ( count( $line_temp ) > 1 ) {
			$line = $line_temp[1];
			$link = wfMessage( $line_temp[0] )->inContentLanguage()->text();
		} else {
			$line = $line_temp[0];
			$link = $line_temp[0];
		}

		// Determine what to show as the human-readable link description
		if ( wfMessage( $line )->isDisabled() ) {
			// It's *not* the name of a MediaWiki message, so display it as-is
			$text = $line;
		} else {
			// Guess what -- it /is/ a MediaWiki message!
			$text = wfMessage( $line )->text();
		}

		if ( $link != null ) {
			if ( wfMessage( $line_temp[0] )->isDisabled() ) {
				$link = $line_temp[0];
			}
			if ( preg_match( '/^(?:' . wfUrlProtocols() . ')/', $link ) ) {
				$href = $link;
			} else {
				$title = Title::newFromText( $link );
				if ( $title ) {
					$title = $title->fixSpecialName();
					$href = $title->getLocalURL();
				} else {
					$href = '#';
				}
			}
		}

		return array(
			'text' => $text,
			'href' => $href
		);
	}

	/**
	 * @param $sidebar array
	 */
	protected function renderPortals( $sidebar ) {
		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = false;
		$sidebar['LANGUAGES'] = false;

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
				<?php echo $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) );
				# echo $this->makeSearchButton( 'fulltext', array( 'id' => 'mw-searchButton', 'class' => 'searchButton' ) );
				?>
				<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
			</div>
		</form>
		</div>
	<?php
	}

	/**
	 * Prints the cactions bar.
	 * Shared between Monobook and Modern and stolen for mask
	 */
	function cactions() {
	?>
		<div id="p-cactions" class="portlet" role="navigation">
			<div class="pBody">
				<ul>
				<?php
					foreach ( $this->data['content_actions'] as $key => $tab ) {
						echo '
					' . $this->makeListItem( $key, $tab );
					}

					// what links here
					if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
						$title = SpecialPage::getTitleFor( 'Whatlinkshere', $this->getSkin()->getTitle() );
						$link = Linker::link( $title, wfMessage( 'mask-whatlinkshere' )->text() ); ?>
						<li id="ca-links"><?php echo $link; ?></li>
						<?php
					}
					// purge
					$title = $this->getSkin()->getTitle();
					$link = Linker::link( $title, wfMessage( 'mask-refresh' )->text(), array(), array( 'action' => 'purge' ) ); ?>
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
			<?php
				foreach ( $cont as $key => $val ) {
					echo $this->makeListItem( $key, $val );
				}
			?>
			</ul>
			<?php
		} else {
			# allow raw HTML block to be defined by extensions
			print $cont;
		}
		?>
		</div>
	</div>
	<?php
	}

	/*************************************************************************************************/
	/**
	* Get URL to the logo image, either a custom one
	* ([[File:Aurora-skin-logo.png]]) or a "sane default" if a custom logo
	* doesn't exist.
	*
	* @return String: logo image URL
	*/
	function getLogoURL() {
		global $wgStylePath;

		$s = '';
		// If there is a custom logo, display it; otherwise show the skin's
		// default logo image
		$logo = wfFindFile( 'Mask_skin_coin.png' );
		if ( is_object( $logo ) ) {
			$s .= $logo->getUrl();
		} else {
			$s .= $wgStylePath . '/Mask/resources/images/skull-coin.png';
		}

		return $s;
	}
} // end of class
