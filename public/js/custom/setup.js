// location.protocol gives http or https
// location.hostname gives hostname
var myUrl = location.protocol + "//" + location.hostname + "/";

// ajax setup for csrf tokens
$.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});
