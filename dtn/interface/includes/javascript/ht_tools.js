// Cette fonction permet de vérifier la validité d'une date au format aaaa-mm-dd 
function isDate(d) {
  if (d == "") 
        return false;
     e = new RegExp("^([0-9]{2}|[0-9]{4})\-[0-9]{1,2}\-[0-9]{1,2}$");
    if (!e.test(d)) 
         return false; 
     a = parseInt(d.split("-")[0], 10); // YYYY
     m = parseInt(d.split("-")[1], 10); // MM
     j = parseInt(d.split("-")[2], 10); // DD
   if (a%4 == 0 && a%100 !=0 || a%400 == 0) feb = 29;
    else feb = 28;
   nbJours = new Array(31,feb,31,30,31,30,31,31,30,31,30,31);
   return ( m >= 1 && m <=12 && j >= 1 && j <= nbJours[m-1] );
}

function cleanSpace(field){
  var j=0;
  var rtrim = /^(.*\S)\s+$/;
  var ltrim = /^\s+(.*\S)$/;
  var mspace=/^(.*)\s+(\S+.*)$/;
  field=field.replace(rtrim,"$1");
  field=field.replace(ltrim,"$1");
  while(field.match(mspace)&&j<=10){field=field.replace(mspace,"$1$2");j++;}j=0;
  return field;
  
}
function cleanIdHattrick(field){
  var j=0;
  var  reg = /^\((\d+)\)$/;
  field=field.replace(reg,"$1");

  return field;
  
}
function cleanStarForm(field){
  var j=0;
  var comma = /^(.*),+(.*)$/;
  field=field.replace(comma,"$1\.$2");
  return field;
  
}