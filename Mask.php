<?php
/**
 * Mask skin - created from the bones of monobook and nimbus
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 * @author Calimonius the Estrange
 * @authors Whoever wrote monobook
 * @date 2013
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

// Skin credits that will show up on Special:Version

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Mask skin',
	'version' => '0.6.1',
	'author' => array( 'Calimonius the Estrange', 'Jack Phoenix' ),
	'descriptionmsg' => 'mask-desc',
	'url' => 'https://github.com/zaori/mw-skin-mask',
);

# Autoload the skin class, make it a valid skin, set up i18n
$skinID = basename( dirname( __FILE__ ) );
$dir = dirname( __FILE__ ) . '/';

# The first instance must be strtolower()ed so that useskin=nimbus works and
# so that it does *not* force an initial capital (i.e. we do NOT want
# useskin=Mask) and the second instance is used to determine the name of
# *this* file.
$wgValidSkinNames[strtolower( $skinID )] = 'Mask';

$wgAutoloadClasses['SkinMask'] = $dir . 'Mask.skin.php';
$wgMessagesDirs['SkinMask'] = $dir . '/i18n';
$wgExtensionMessagesFiles['SkinMask'] = $dir . 'Mask.i18n.php';
$wgResourceModules['skins.mask'] = array(
	'styles' => array(
		'skins/Mask/resources/normalise.css' => array( 'media' => 'screen' ),
		'skins/Mask/resources/fonts.css' => array( 'media' => 'screen' ),
		'skins/Mask/resources/main.css' => array( 'media' => 'screen' )
	),
	'position' => 'top'
);

# Get rid of wm.ui form styling
$wgResourceModuleSkinStyles['mask'] = array(
	'mediawiki.ui' => array(),
	'mediawiki.ui.checkbox' => array(),
	'mediawiki.ui.anchor' => array(),
	'mediawiki.ui.button' => array(),
	'mediawiki.ui.input' => array(),
	'mediawiki.ui.radio' => array(),
	'mediawiki.ui.icon' => array(),
	'mediawiki.ui.text' => array(),
);
