/** 
 * @Name		Frozen Fixes (based on Eddie Machado's Bones Scripts)
 * @URI			http://pxoink.net/frozen-bones
 * @Author		PxO Ink
 * @AuthorURI	http://pxoink.net/
 * @License		MIT
 * @Copyright	© 2015 PxO Ink. All Rights Reserved.
 */

/**
 * IE8 Polyfill
 */
if(!window.getComputedStyle){window.getComputedStyle=function(e,t){this.el=e;this.getPropertyValue=function(t){var n=/(\-([a-z]){1})/g;if(t=="float")t="styleFloat";if(n.test(t)){t=t.replace(n,function(){return arguments[2].toUpperCase()})}return e.currentStyle[t]?e.currentStyle[t]:null};return this}}

/**
 * Fixes iOS orientationchange zoom bug.
 * @Author	@scottjehl, @wilto
 * @License	MIT
 */
(function(e){function c(){n.setAttribute("content",s);o=true}function h(){n.setAttribute("content",i);o=false}function p(t){l=t.accelerationIncludingGravity;u=Math.abs(l.x);a=Math.abs(l.y);f=Math.abs(l.z);if(!e.orientation&&(u>7||(f>6&&a<8||f<8&&a>6)&&u>5)){if(o){h()}}else if(!o){c()}}if(!(/iPhone|iPad|iPod/.test(navigator.platform)&&navigator.userAgent.indexOf("AppleWebKit")>-1)){return}var t=e.document;if(!t.querySelector){return}var n=t.querySelector("meta[name=viewport]"),r=n&&n.getAttribute("content"),i=r+",maximum-scale=1",s=r+",maximum-scale=10",o=true,u,a,f,l;if(!n){return}e.addEventListener("orientationchange",c,false);e.addEventListener("devicemotion",p,false)})(this)