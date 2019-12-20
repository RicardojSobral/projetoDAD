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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, exports) {

throw new Error("Module build failed (from ./node_modules/babel-loader/lib/index.js):\nSyntaxError: C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\resources\\js\\app.js: Unexpected token, expected \",\" (52:5)\n\n\u001b[0m \u001b[90m 50 | \u001b[39m     { path\u001b[33m:\u001b[39m \u001b[32m'/home'\u001b[39m\u001b[33m,\u001b[39m component\u001b[33m:\u001b[39m home }\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 51 | \u001b[39m     { path\u001b[33m:\u001b[39m \u001b[32m'/accountcreate'\u001b[39m\u001b[33m,\u001b[39m component\u001b[33m:\u001b[39m accountCreate}\u001b[0m\n\u001b[0m\u001b[31m\u001b[1m>\u001b[22m\u001b[39m\u001b[90m 52 | \u001b[39m     { path\u001b[33m:\u001b[39m \u001b[32m'/home'\u001b[39m\u001b[33m,\u001b[39m component\u001b[33m:\u001b[39m home}\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m    | \u001b[39m     \u001b[31m\u001b[1m^\u001b[22m\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 53 | \u001b[39m     { path\u001b[33m:\u001b[39m \u001b[32m'/login'\u001b[39m\u001b[33m,\u001b[39m component\u001b[33m:\u001b[39m login\u001b[33m,\u001b[39m  meta\u001b[33m:\u001b[39m{ requiresVisitor\u001b[33m:\u001b[39m \u001b[36mtrue\u001b[39m } }\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 54 | \u001b[39m     { path\u001b[33m:\u001b[39m \u001b[32m'/logout'\u001b[39m\u001b[33m,\u001b[39m component\u001b[33m:\u001b[39m logout\u001b[33m,\u001b[39m meta\u001b[33m:\u001b[39m{ requiresAuth\u001b[33m:\u001b[39m \u001b[36mtrue\u001b[39m } }\u001b[33m,\u001b[39m\u001b[0m\n\u001b[0m \u001b[90m 55 | \u001b[39m     { path\u001b[33m:\u001b[39m \u001b[32m'/profile'\u001b[39m\u001b[33m,\u001b[39m component\u001b[33m:\u001b[39m profile\u001b[33m,\u001b[39m meta\u001b[33m:\u001b[39m{ requiresAuth\u001b[33m:\u001b[39m \u001b[36mtrue\u001b[39m } }\u001b[33m,\u001b[39m\u001b[0m\n    at Parser.raise (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:6930:17)\n    at Parser.unexpected (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:8323:16)\n    at Parser.expect (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:8309:28)\n    at Parser.parseExprList (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:10214:14)\n    at Parser.parseExprAtom (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:9511:32)\n    at Parser.parseExprSubscripts (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:9165:23)\n    at Parser.parseMaybeUnary (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:9145:21)\n    at Parser.parseExprOps (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:9011:23)\n    at Parser.parseMaybeConditional (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:8984:23)\n    at Parser.parseMaybeAssign (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:8930:21)\n    at Parser.parseVar (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:11262:26)\n    at Parser.parseVarStatement (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:11081:10)\n    at Parser.parseStatementContent (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:10678:21)\n    at Parser.parseStatement (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:10611:17)\n    at Parser.parseBlockOrModuleBlockBody (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:11187:25)\n    at Parser.parseBlockBody (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:11174:10)\n    at Parser.parseTopLevel (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:10542:10)\n    at Parser.parse (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:12051:10)\n    at parse (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\parser\\lib\\index.js:12102:38)\n    at parser (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\core\\lib\\transformation\\normalize-file.js:168:34)\n    at normalizeFile (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\core\\lib\\transformation\\normalize-file.js:102:11)\n    at runSync (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\core\\lib\\transformation\\index.js:44:43)\n    at runAsync (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\core\\lib\\transformation\\index.js:35:14)\n    at process.nextTick (C:\\Users\\rjoao\\ainet\\dadAulas\\projetoDAD\\node_modules\\@babel\\core\\lib\\transform.js:34:34)\n    at process._tickCallback (internal/process/next_tick.js:61:11)");

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\Users\rjoao\ainet\dadAulas\projetoDAD\resources\js\app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! C:\Users\rjoao\ainet\dadAulas\projetoDAD\resources\sass\app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });