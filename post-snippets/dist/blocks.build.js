/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block_block_js__ = __webpack_require__(/*! ./block/block.js */ 1);\n/**\n * Gutenberg Blocks\n *\n * All blocks related JavaScript files should be imported here.\n * You can create a new block folder in this dir and include code\n * for that block here as well.\n *\n * All blocks should be included here since this is the file that\n * Webpack is compiling as the input file.\n */\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9ja3MuanM/N2I1YiJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEd1dGVuYmVyZyBCbG9ja3NcbiAqXG4gKiBBbGwgYmxvY2tzIHJlbGF0ZWQgSmF2YVNjcmlwdCBmaWxlcyBzaG91bGQgYmUgaW1wb3J0ZWQgaGVyZS5cbiAqIFlvdSBjYW4gY3JlYXRlIGEgbmV3IGJsb2NrIGZvbGRlciBpbiB0aGlzIGRpciBhbmQgaW5jbHVkZSBjb2RlXG4gKiBmb3IgdGhhdCBibG9jayBoZXJlIGFzIHdlbGwuXG4gKlxuICogQWxsIGJsb2NrcyBzaG91bGQgYmUgaW5jbHVkZWQgaGVyZSBzaW5jZSB0aGlzIGlzIHRoZSBmaWxlIHRoYXRcbiAqIFdlYnBhY2sgaXMgY29tcGlsaW5nIGFzIHRoZSBpbnB1dCBmaWxlLlxuICovXG5cbmltcG9ydCAnLi9ibG9jay9ibG9jay5qcyc7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2tzLmpzXG4vLyBtb2R1bGUgaWQgPSAwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */
/*!****************************!*\
  !*** ./src/block/block.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 2);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__editor_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__style_scss__ = __webpack_require__(/*! ./style.scss */ 3);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_lodash__ = __webpack_require__(/*! lodash */ 4);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_lodash___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_lodash__);\n/**\n * BLOCK: post-snippets-block\n *\n * Registering a basic block with Gutenberg.\n * Simple block, renders and saves the same content without any interactivity.\n */\n\n//  Import CSS.\n\n\n\n\nvar __ = wp.i18n.__; // Import __() from wp.i18n\n\nvar registerBlockType = wp.blocks.registerBlockType; // Import registerBlockType() from wp.blocks\n\nvar _wp$components = wp.components,\n    SelectControl = _wp$components.SelectControl,\n    TextControl = _wp$components.TextControl;\n\n\nvar vars_dirty = {};\nregisterBlockType('greentreelabs/post-snippets-block', {\n\n\ttitle: __('Post Snippets'),\n\ticon: 'admin-plugins',\n\tcategory: 'common',\n\tkeywords: [__('post snippets'), __('snippets')],\n\tattributes: {\n\t\tsnippet: {\n\t\t\ttype: 'string'\n\t\t},\n\t\tvars: {\n\t\t\ttype: 'string'\n\t\t},\n\t\tshortcode: {\n\t\t\ttype: 'boolean'\n\t\t},\n\t\ttext_fields: {\n\t\t\ttype: 'array'\n\t\t}\n\t},\n\n\tedit: function edit(props) {\n\t\tvar _props$attributes = props.attributes,\n\t\t    snippet = _props$attributes.snippet,\n\t\t    vars = _props$attributes.vars,\n\t\t    text_fields = _props$attributes.text_fields,\n\t\t    shortcode = _props$attributes.shortcode;\n\n\n\t\tconsole.log(\"text_fields\", text_fields);\n\n\t\tvar options = __WEBPACK_IMPORTED_MODULE_2_lodash___default.a.concat([{ label: __('----'), value: \"\" }], post_snippets_s.map(function (s) {\n\t\t\treturn { label: s.title, value: s.title };\n\t\t}));\n\n\t\tfunction save(s, v) {\n\t\t\tvars_dirty[s] = v;\n\t\t\tvar vars_temp = [];\n\t\t\tfor (var k in vars_dirty) {\n\t\t\t\tvars_temp.push(k + \"=\" + vars_dirty[k]);\n\t\t\t}\n\t\t\tprops.setAttributes({ vars: vars_temp.join(\" \") });\n\t\t}\n\n\t\tfunction getVarValue(field) {\n\t\t\tif (!vars) return \"\";\n\n\t\t\tvar all = vars.split(\" \");\n\t\t\tvar value = \"\";\n\n\t\t\tall.forEach(function (v) {\n\t\t\t\tvar els = v.split(\"=\");\n\n\t\t\t\tif (els[0] != field) return;\n\n\t\t\t\tif (els.length > 1) {\n\t\t\t\t\tvalue = els[1];\n\t\t\t\t}\n\t\t\t});\n\t\t\treturn value;\n\t\t}\n\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\tnull,\n\t\t\twp.element.createElement(SelectControl, {\n\t\t\t\tlabel: 'Snippet',\n\t\t\t\tvalue: snippet,\n\t\t\t\toptions: options,\n\t\t\t\tonChange: function onChange(v) {\n\t\t\t\t\tprops.setAttributes({ snippet: v });\n\n\t\t\t\t\tvar s = __WEBPACK_IMPORTED_MODULE_2_lodash___default.a.find(post_snippets_s, function (e) {\n\t\t\t\t\t\treturn e.title == v;\n\t\t\t\t\t});\n\t\t\t\t\tprops.setAttributes({\n\t\t\t\t\t\ttext_fields: s.vars != \"\" ? s.vars.split(\",\") : []\n\t\t\t\t\t});\n\t\t\t\t\tprops.setAttributes({\n\t\t\t\t\t\tshortcode: s.shortcode != \"0\"\n\t\t\t\t\t});\n\t\t\t\t}\n\t\t\t}),\n\t\t\ttext_fields ? text_fields.map(function (e) {\n\t\t\t\treturn e ? wp.element.createElement(TextControl, {\n\t\t\t\t\tlabel: e,\n\t\t\t\t\tvalue: getVarValue(e),\n\t\t\t\t\tonChange: function onChange(v) {\n\t\t\t\t\t\tsave(e, v);\n\t\t\t\t\t}\n\t\t\t\t}) : \"\";\n\t\t\t}) : \"\"\n\t\t);\n\t},\n\n\tsave: function save(props) {\n\t\tconsole.log(\"save\");\n\t\tconsole.log(props);\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\tnull,\n\t\t\tprops.attributes.shortcode ? \"[\" : \"\",\n\t\t\tprops.attributes.snippet,\n\t\t\t' ',\n\t\t\tprops.attributes.vars,\n\t\t\tprops.attributes.shortcode ? \"]\" : \"\"\n\t\t);\n\t}\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMS5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9ibG9jay5qcz85MjFkIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQkxPQ0s6IHBvc3Qtc25pcHBldHMtYmxvY2tcbiAqXG4gKiBSZWdpc3RlcmluZyBhIGJhc2ljIGJsb2NrIHdpdGggR3V0ZW5iZXJnLlxuICogU2ltcGxlIGJsb2NrLCByZW5kZXJzIGFuZCBzYXZlcyB0aGUgc2FtZSBjb250ZW50IHdpdGhvdXQgYW55IGludGVyYWN0aXZpdHkuXG4gKi9cblxuLy8gIEltcG9ydCBDU1MuXG5pbXBvcnQgJy4vZWRpdG9yLnNjc3MnO1xuaW1wb3J0ICcuL3N0eWxlLnNjc3MnO1xuaW1wb3J0IF8gZnJvbSAnbG9kYXNoJztcblxudmFyIF9fID0gd3AuaTE4bi5fXzsgLy8gSW1wb3J0IF9fKCkgZnJvbSB3cC5pMThuXG5cbnZhciByZWdpc3RlckJsb2NrVHlwZSA9IHdwLmJsb2Nrcy5yZWdpc3RlckJsb2NrVHlwZTsgLy8gSW1wb3J0IHJlZ2lzdGVyQmxvY2tUeXBlKCkgZnJvbSB3cC5ibG9ja3NcblxudmFyIF93cCRjb21wb25lbnRzID0gd3AuY29tcG9uZW50cyxcbiAgICBTZWxlY3RDb250cm9sID0gX3dwJGNvbXBvbmVudHMuU2VsZWN0Q29udHJvbCxcbiAgICBUZXh0Q29udHJvbCA9IF93cCRjb21wb25lbnRzLlRleHRDb250cm9sO1xuXG5cbnZhciB2YXJzX2RpcnR5ID0ge307XG5yZWdpc3RlckJsb2NrVHlwZSgnZ3JlZW50cmVlbGFicy9wb3N0LXNuaXBwZXRzLWJsb2NrJywge1xuXG5cdHRpdGxlOiBfXygnUG9zdCBTbmlwcGV0cycpLFxuXHRpY29uOiAnYWRtaW4tcGx1Z2lucycsXG5cdGNhdGVnb3J5OiAnY29tbW9uJyxcblx0a2V5d29yZHM6IFtfXygncG9zdCBzbmlwcGV0cycpLCBfXygnc25pcHBldHMnKV0sXG5cdGF0dHJpYnV0ZXM6IHtcblx0XHRzbmlwcGV0OiB7XG5cdFx0XHR0eXBlOiAnc3RyaW5nJ1xuXHRcdH0sXG5cdFx0dmFyczoge1xuXHRcdFx0dHlwZTogJ3N0cmluZydcblx0XHR9LFxuXHRcdHNob3J0Y29kZToge1xuXHRcdFx0dHlwZTogJ2Jvb2xlYW4nXG5cdFx0fSxcblx0XHR0ZXh0X2ZpZWxkczoge1xuXHRcdFx0dHlwZTogJ2FycmF5J1xuXHRcdH1cblx0fSxcblxuXHRlZGl0OiBmdW5jdGlvbiBlZGl0KHByb3BzKSB7XG5cdFx0dmFyIF9wcm9wcyRhdHRyaWJ1dGVzID0gcHJvcHMuYXR0cmlidXRlcyxcblx0XHQgICAgc25pcHBldCA9IF9wcm9wcyRhdHRyaWJ1dGVzLnNuaXBwZXQsXG5cdFx0ICAgIHZhcnMgPSBfcHJvcHMkYXR0cmlidXRlcy52YXJzLFxuXHRcdCAgICB0ZXh0X2ZpZWxkcyA9IF9wcm9wcyRhdHRyaWJ1dGVzLnRleHRfZmllbGRzLFxuXHRcdCAgICBzaG9ydGNvZGUgPSBfcHJvcHMkYXR0cmlidXRlcy5zaG9ydGNvZGU7XG5cblxuXHRcdGNvbnNvbGUubG9nKFwidGV4dF9maWVsZHNcIiwgdGV4dF9maWVsZHMpO1xuXG5cdFx0dmFyIG9wdGlvbnMgPSBfLmNvbmNhdChbeyBsYWJlbDogX18oJy0tLS0nKSwgdmFsdWU6IFwiXCIgfV0sIHBvc3Rfc25pcHBldHNfcy5tYXAoZnVuY3Rpb24gKHMpIHtcblx0XHRcdHJldHVybiB7IGxhYmVsOiBzLnRpdGxlLCB2YWx1ZTogcy50aXRsZSB9O1xuXHRcdH0pKTtcblxuXHRcdGZ1bmN0aW9uIHNhdmUocywgdikge1xuXHRcdFx0dmFyc19kaXJ0eVtzXSA9IHY7XG5cdFx0XHR2YXIgdmFyc190ZW1wID0gW107XG5cdFx0XHRmb3IgKHZhciBrIGluIHZhcnNfZGlydHkpIHtcblx0XHRcdFx0dmFyc190ZW1wLnB1c2goayArIFwiPVwiICsgdmFyc19kaXJ0eVtrXSk7XG5cdFx0XHR9XG5cdFx0XHRwcm9wcy5zZXRBdHRyaWJ1dGVzKHsgdmFyczogdmFyc190ZW1wLmpvaW4oXCIgXCIpIH0pO1xuXHRcdH1cblxuXHRcdGZ1bmN0aW9uIGdldFZhclZhbHVlKGZpZWxkKSB7XG5cdFx0XHRpZiAoIXZhcnMpIHJldHVybiBcIlwiO1xuXG5cdFx0XHR2YXIgYWxsID0gdmFycy5zcGxpdChcIiBcIik7XG5cdFx0XHR2YXIgdmFsdWUgPSBcIlwiO1xuXG5cdFx0XHRhbGwuZm9yRWFjaChmdW5jdGlvbiAodikge1xuXHRcdFx0XHR2YXIgZWxzID0gdi5zcGxpdChcIj1cIik7XG5cblx0XHRcdFx0aWYgKGVsc1swXSAhPSBmaWVsZCkgcmV0dXJuO1xuXG5cdFx0XHRcdGlmIChlbHMubGVuZ3RoID4gMSkge1xuXHRcdFx0XHRcdHZhbHVlID0gZWxzWzFdO1xuXHRcdFx0XHR9XG5cdFx0XHR9KTtcblx0XHRcdHJldHVybiB2YWx1ZTtcblx0XHR9XG5cblx0XHRyZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuXHRcdFx0J2RpdicsXG5cdFx0XHRudWxsLFxuXHRcdFx0d3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFNlbGVjdENvbnRyb2wsIHtcblx0XHRcdFx0bGFiZWw6ICdTbmlwcGV0Jyxcblx0XHRcdFx0dmFsdWU6IHNuaXBwZXQsXG5cdFx0XHRcdG9wdGlvbnM6IG9wdGlvbnMsXG5cdFx0XHRcdG9uQ2hhbmdlOiBmdW5jdGlvbiBvbkNoYW5nZSh2KSB7XG5cdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyh7IHNuaXBwZXQ6IHYgfSk7XG5cblx0XHRcdFx0XHR2YXIgcyA9IF8uZmluZChwb3N0X3NuaXBwZXRzX3MsIGZ1bmN0aW9uIChlKSB7XG5cdFx0XHRcdFx0XHRyZXR1cm4gZS50aXRsZSA9PSB2O1xuXHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdHByb3BzLnNldEF0dHJpYnV0ZXMoe1xuXHRcdFx0XHRcdFx0dGV4dF9maWVsZHM6IHMudmFycyAhPSBcIlwiID8gcy52YXJzLnNwbGl0KFwiLFwiKSA6IFtdXG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0cHJvcHMuc2V0QXR0cmlidXRlcyh7XG5cdFx0XHRcdFx0XHRzaG9ydGNvZGU6IHMuc2hvcnRjb2RlICE9IFwiMFwiXG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdH1cblx0XHRcdH0pLFxuXHRcdFx0dGV4dF9maWVsZHMgPyB0ZXh0X2ZpZWxkcy5tYXAoZnVuY3Rpb24gKGUpIHtcblx0XHRcdFx0cmV0dXJuIGUgPyB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoVGV4dENvbnRyb2wsIHtcblx0XHRcdFx0XHRsYWJlbDogZSxcblx0XHRcdFx0XHR2YWx1ZTogZ2V0VmFyVmFsdWUoZSksXG5cdFx0XHRcdFx0b25DaGFuZ2U6IGZ1bmN0aW9uIG9uQ2hhbmdlKHYpIHtcblx0XHRcdFx0XHRcdHNhdmUoZSwgdik7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9KSA6IFwiXCI7XG5cdFx0XHR9KSA6IFwiXCJcblx0XHQpO1xuXHR9LFxuXG5cdHNhdmU6IGZ1bmN0aW9uIHNhdmUocHJvcHMpIHtcblx0XHRjb25zb2xlLmxvZyhcInNhdmVcIik7XG5cdFx0Y29uc29sZS5sb2cocHJvcHMpO1xuXHRcdHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG5cdFx0XHQnZGl2Jyxcblx0XHRcdG51bGwsXG5cdFx0XHRwcm9wcy5hdHRyaWJ1dGVzLnNob3J0Y29kZSA/IFwiW1wiIDogXCJcIixcblx0XHRcdHByb3BzLmF0dHJpYnV0ZXMuc25pcHBldCxcblx0XHRcdCcgJyxcblx0XHRcdHByb3BzLmF0dHJpYnV0ZXMudmFycyxcblx0XHRcdHByb3BzLmF0dHJpYnV0ZXMuc2hvcnRjb2RlID8gXCJdXCIgOiBcIlwiXG5cdFx0KTtcblx0fVxufSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2svYmxvY2suanNcbi8vIG1vZHVsZSBpZCA9IDFcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///1\n");

/***/ }),
/* 2 */
/*!*******************************!*\
  !*** ./src/block/editor.scss ***!
  \*******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9lZGl0b3Iuc2Nzcz80OWQyIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2svZWRpdG9yLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDJcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///2\n");

/***/ }),
/* 3 */
/*!******************************!*\
  !*** ./src/block/style.scss ***!
  \******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9zdHlsZS5zY3NzPzgwZjMiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9ibG9jay9zdHlsZS5zY3NzXG4vLyBtb2R1bGUgaWQgPSAzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///3\n");

/***/ }),
/* 4 */
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! dynamic exports provided */
/*! exports used: default */
/***/ (function(module, exports) {

module.exports = lodash;

/***/ })
/******/ ]);