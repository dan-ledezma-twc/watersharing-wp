/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	 icon: {
		 src: <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		 <path d="M14.7508 5.4C16.8369 7.09412 18.5354 10.1471 18.5354 12.8647C18.5354 16.7294 15.0646 19.2706 12 19.2706C8.93538 19.2706 5.39077 16.5882 5.39077 12.7235C5.39077 10.1118 6.77538 7.37647 8.65846 5.62941C5.77846 6.84706 3.78461 9.6 3.78461 12.8118C3.78461 17.1353 7.45846 20.6471 12 20.6471C16.5415 20.6471 20.2154 17.1353 20.2154 12.7941C20.2154 9.38824 17.9262 6.47647 14.7508 5.4Z" fill="black"/>
		 <path d="M12 18.1059C8.97231 18.1059 6.68307 15.7941 6.68307 12.7235C6.68307 9.26471 11.3169 4.11177 11.5015 3.9L12 3.35294L12.48 3.91765C12.6646 4.12941 17.1508 9.3353 17.1508 12.7412C17.1692 15.8471 14.9908 18.1059 12 18.1059ZM11.9815 5.29412C10.8 6.70588 7.97538 10.3412 7.97538 12.7412C7.97538 15.1059 9.71077 16.8882 12 16.8882C14.2708 16.8882 15.8769 15.1765 15.8769 12.7412C15.8769 10.3765 13.1262 6.70588 11.9815 5.29412Z" fill="black"/>
		 <path d="M15.1569 12.0529C14.1231 13.0588 13.5323 12.9706 12.4431 12.8118C11.3169 12.6353 11.0031 12.1059 9.84 12.1059C9.43384 12.1059 9.17538 12.1588 9.00923 12.2294C8.9723 12.4235 8.95384 12.6176 8.95384 12.8118C8.95384 14.4529 10.3569 15.7765 12.1108 15.7765C13.8461 15.7765 15.2677 14.4529 15.2677 12.8118C15.2677 12.5471 15.2308 12.3 15.1569 12.0529ZM9.6923 13.9235C11.4092 15.0176 12.7569 15.2471 14.4185 13.9235C13.3846 15.8824 10.8369 15.6529 9.6923 13.9235Z" fill="black"/>
		 </svg>
	 },
	edit: Edit,
} );
