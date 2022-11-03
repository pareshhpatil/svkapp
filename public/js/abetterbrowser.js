// View on GitHub: https://github.com/xPaw/CF-ABetterBrowser

CloudFlare.define( 'abetterbrowser', [ 'cloudflare/dom', 'cloudflare/user', 'abetterbrowser/config' ], function( version, user, config )
{	/**
	 * Did user close this message already?
	 */
	if( user.getCookie( 'cfapp_abetterbrowser' ) === 1 )
	{
		return;
	}
	
	/**
	 * Compare each version and if there is one that matches
	 */
	var matched, i = 0, versions =
	[
		[ version.internetExplorer, ( parseInt( config.ie, 10 ) || 8 ) ],
		[ version.firefox         , ( parseFloat( config.firefox ) || 0 ) ],
		[ version.chrome          , ( parseFloat( config.chrome ) || 0 ) ],
		[ version.opera           , ( parseFloat( config.opera ) || 0 ) ],
		[ version.safari          , ( parseFloat( config.safari ) || 0 ) ]
	], length = versions.length;
	
	for( ; i < length; i++ )
	{
		version = versions[ i ];
		
		if( version[ 0 ] && version[ 1 ] >= version[ 0 ] )
		{
			matched = true;
			
			break;
		}
	}
	
	if( !matched )
	{
		return;
	}
	
	var nav = window.navigator, doc = document,
	
	/**
	 * Detect user's browser language
	 */
	language = ( nav.language || nav.browserLanguage || 'en' ).toLowerCase(),
	
	/**
	 * Whatbrowser link with user's language
	 */
	moreInformationLink = '<a href="http://www.whatbrowser.org/intl/' + language + '/" target="_blank">',
	
	/**
	 * Translations
	 * 
	 * moreInformationLink contains , and the element is closed before the message is inserted,
	 * this is done to save some bytes when it is minified.
	 */
	translations =
	{
		'en': 'This version of Internet Explorer is no longer supported. Please upgrade to a ' + moreInformationLink + 'supported browser &#187;',
		
	},
	
	/**
	 * Style rules
	 */
	rules =
		'#cloudflare-old-browser {' +
			'background:#2c5294;' +
			'position:absolute;' +
			'z-index:1;' +
			'width:984px;' +
			'height:25px;' +
			'top:-16px;' +
			'left:80px;' +
			'overflow:hidden;' +
			'padding:6px 4px 2px 4px;' +
			'font:15px/25px Arial,sans-serif;' +
			'text-align:center;' +
			'color:#FFF;' +
			'box-sizing:content-box' +
		'}' +
		
		'#cloudflare-old-browser a {' +
			'text-decoration:underline;' +
			'color:#EBEBF4' +
		'}' +
		
		'#cloudflare-old-browser a:hover, #cloudflare-old-browser a:active {' +
			'text-decoration:none;' +
			'color:#DBDBEB' +
		'}' +
		
		'#cloudflare-old-browser-close {' +
			'background:#2c5294;' +
			'display:block;' +
			'width:15px;' +
			'height:15px;' +
			'position:absolute;' +
			'text-decoration:none !important;' +
			'cursor:pointer;' +
			'top:0;' +
			'right:0;' +
			'font-size:15px;' +
			'line-height:15px' +
		'}' +
		
		'#cloudflare-old-browser-close:hover {' +
			'background:#E04343;' +
			'color:#FFF' +
		'}',
	
	/**
	 * Have a single var statement, so it compiles better
	 */
	body = doc.body,
	head = doc.getElementsByTagName( 'head' )[ 0 ],
	message = doc.createElement( 'div' ),
	closeButton = doc.createElement( 'a' ),
	
	/**
	 * Injects style rules into the document to handle formatting
	 */
	style = doc.createElement( 'style' );
	style.id = 'cloudflare-abetterbrowser';
	style.setAttribute( 'type', 'text/css' );
	
	if( style.styleSheet )
	{
		style.styleSheet.cssText = rules;
	}
	else
	{
		style.appendChild( doc.createTextNode( rules ) );
	}
	
	head.insertBefore( style, head.firstChild );
	
	/**
	 * Message container
	 */
	message.id = 'cloudflare-old-browser';
	message.innerHTML = ( translations[ language ] || translations[ language.substring( 0, 2 ) ] || translations.en ) + '</a>';
	
	/**
	 * Close button
	 */
	closeButton.id = 'cloudflare-old-browser-close';
	closeButton.innerHTML = '&times;';
	
	message.appendChild( closeButton );
	
	closeButton.onclick = function( )
	{
		body.removeChild( message );
		body.className = body.className.replace( new RegExp( '(\\s|^)cloudflare-old-browser-body(\\s|$)' ), '' );
		
		/**
		 * Hide message for 7 days
		 */
		user.getCookie( 'cfapp_abetterbrowser', 1, 7 );
		
		return false;
	};
	
	/**
	 * Injects our message into the body
	 */
	body.appendChild( message );
	body.className += ' cloudflare-old-browser-body';
} );
