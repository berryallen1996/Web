CKEDITOR.dialog.add("scaytDialog",function(c){var f=c.scayt,q='\x3cp\x3e\x3cimg src\x3d"'+f.getLogo()+'" /\x3e\x3c/p\x3e\x3cp\x3e'+f.getLocal("version")+f.getVersion()+"\x3c/p\x3e\x3cp\x3e"+f.getLocal("text_copyrights")+"\x3c/p\x3e",r=CKEDITOR.document,n={isChanged:function(){return null===this.newLang||this.currentLang===this.newLang?!1:!0},currentLang:f.getLang(),newLang:null,reset:function(){this.currentLang=f.getLang();this.newLang=null},id:"lang"},q=[{id:"options",label:f.getLocal("tab_options"),
onShow:function(){},elements:[{type:"vbox",id:"scaytOptions",children:function(){var a=f.getApplicationConfig(),b=[],g={"ignore-all-caps-words":"label_allCaps","ignore-domain-names":"label_ignoreDomainNames","ignore-words-with-mixed-cases":"label_mixedCase","ignore-words-with-numbers":"label_mixedWithDigits"},e;for(e in a)a={type:"checkbox"},a.id=e,a.label=f.getLocal(g[e]),b.push(a);return b}(),onShow:function(){this.getChild();for(var a=c.scayt,b=0;b<this.getChild().length;b++)this.getChild()[b].setValue(a.getApplicationConfig()[this.getChild()[b].id])}}]},
{id:"langs",label:f.getLocal("tab_languages"),elements:[{id:"leftLangColumn",type:"vbox",align:"left",widths:["100"],children:[{type:"html",id:"langBox",style:"overflow: hidden; white-space: normal;margin-bottom:15px;",html:'\x3cdiv\x3e\x3cdiv style\x3d"float:left;width:45%;margin-left:5px;" id\x3d"left-col-'+c.name+'"\x3e\x3c/div\x3e\x3cdiv style\x3d"float:left;width:45%;margin-left:15px;" id\x3d"right-col-'+c.name+'"\x3e\x3c/div\x3e\x3c/div\x3e',onShow:function(){var a=c.scayt.getLang();r.getById("scaytLang_"+
c.name+"_"+a).$.checked=!0}},{type:"html",id:"graytLanguagesHint",html:'\x3cdiv style\x3d"margin:5px auto; width:95%;white-space:normal;" id\x3d"'+c.name+'graytLanguagesHint"\x3e\x3cspan style\x3d"width:10px;height:10px;display: inline-block; background:#02b620;vertical-align:top;margin-top:2px;"\x3e\x3c/span\x3e - This languages are supported by Grammar As You Type(GRAYT).\x3c/div\x3e',onShow:function(){var a=r.getById(c.name+"graytLanguagesHint");c.config.grayt_autoStartup||(a.$.style.display="none")}}]}]},
{id:"dictionaries",label:f.getLocal("tab_dictionaries"),elements:[{type:"vbox",id:"rightCol_col__left",children:[{type:"html",id:"dictionaryNote",html:""},{type:"text",id:"dictionaryName",label:f.getLocal("label_fieldNameDic")||"Dictionary name",onShow:function(a){var b=a.sender,g=c.scayt;setTimeout(function(){b.getContentElement("dictionaries","dictionaryNote").getElement().setText("");null!=g.getUserDictionaryName()&&""!=g.getUserDictionaryName()&&b.getContentElement("dictionaries","dictionaryName").setValue(g.getUserDictionaryName())},
0)}},{type:"hbox",id:"notExistDic",align:"left",style:"width:auto;",widths:["50%","50%"],children:[{type:"button",id:"createDic",label:f.getLocal("btn_createDic"),title:f.getLocal("btn_createDic"),onClick:function(){var a=this.getDialog(),b=p,g=c.scayt,e=a.getContentElement("dictionaries","dictionaryName").getValue();g.createUserDictionary(e,function(d){d.error||b.toggleDictionaryButtons.call(a,!0);d.dialog=a;d.command="create";d.name=e;c.fire("scaytUserDictionaryAction",d)},function(d){d.dialog=
a;d.command="create";d.name=e;c.fire("scaytUserDictionaryActionError",d)})}},{type:"button",id:"restoreDic",label:f.getLocal("btn_restoreDic"),title:f.getLocal("btn_restoreDic"),onClick:function(){var a=this.getDialog(),b=c.scayt,g=p,e=a.getContentElement("dictionaries","dictionaryName").getValue();b.restoreUserDictionary(e,function(d){d.dialog=a;d.error||g.toggleDictionaryButtons.call(a,!0);d.command="restore";d.name=e;c.fire("scaytUserDictionaryAction",d)},function(d){d.dialog=a;d.command="restore";
d.name=e;c.fire("scaytUserDictionaryActionError",d)})}}]},{type:"hbox",id:"existDic",align:"left",style:"width:auto;",widths:["50%","50%"],children:[{type:"button",id:"removeDic",label:f.getLocal("btn_deleteDic"),title:f.getLocal("btn_deleteDic"),onClick:function(){var a=this.getDialog(),b=c.scayt,g=p,e=a.getContentElement("dictionaries","dictionaryName"),d=e.getValue();b.removeUserDictionary(d,function(b){e.setValue("");b.error||g.toggleDictionaryButtons.call(a,!1);b.dialog=a;b.command="remove";
b.name=d;c.fire("scaytUserDictionaryAction",b)},function(b){b.dialog=a;b.command="remove";b.name=d;c.fire("scaytUserDictionaryActionError",b)})}},{type:"button",id:"renameDic",label:f.getLocal("btn_renameDic"),title:f.getLocal("btn_renameDic"),onClick:function(){var a=this.getDialog(),b=c.scayt,g=a.getContentElement("dictionaries","dictionaryName").getValue();b.renameUserDictionary(g,function(b){b.dialog=a;b.command="rename";b.name=g;c.fire("scaytUserDictionaryAction",b)},function(b){b.dialog=a;b.command=
"rename";b.name=g;c.fire("scaytUserDictionaryActionError",b)})}}]},{type:"html",id:"dicInfo",html:'\x3cdiv id\x3d"dic_info_editor1" style\x3d"margin:5px auto; width:95%;white-space:normal;"\x3e'+f.getLocal("text_descriptionDic")+"\x3c/div\x3e"}]}]},{id:"about",label:f.getLocal("tab_about"),elements:[{type:"html",id:"about",style:"margin: 5px 5px;",html:'\x3cdiv\x3e\x3cdiv id\x3d"scayt_about_"\x3e'+q+"\x3c/div\x3e\x3c/div\x3e"}]}];c.on("scaytUserDictionaryAction",function(a){var b=SCAYT.prototype.UILib,
g=a.data.dialog,e=g.getContentElement("dictionaries","dictionaryNote").getElement(),d=a.editor.scayt,c;void 0===a.data.error?(c=d.getLocal("message_success_"+a.data.command+"Dic"),c=c.replace("%s",a.data.name),e.setText(c),b.css(e.$,{color:"blue"})):(""===a.data.name?e.setText(d.getLocal("message_info_emptyDic")):(c=d.getLocal("message_error_"+a.data.command+"Dic"),c=c.replace("%s",a.data.name),e.setText(c)),b.css(e.$,{color:"red"}),null!=d.getUserDictionaryName()&&""!=d.getUserDictionaryName()?g.getContentElement("dictionaries",
"dictionaryName").setValue(d.getUserDictionaryName()):g.getContentElement("dictionaries","dictionaryName").setValue(""))});c.on("scaytUserDictionaryActionError",function(a){var b=SCAYT.prototype.UILib,c=a.data.dialog,e=c.getContentElement("dictionaries","dictionaryNote").getElement(),d=a.editor.scayt,f;""===a.data.name?e.setText(d.getLocal("message_info_emptyDic")):(f=d.getLocal("message_error_"+a.data.command+"Dic"),f=f.replace("%s",a.data.name),e.setText(f));b.css(e.$,{color:"red"});null!=d.getUserDictionaryName()&&
""!=d.getUserDictionaryName()?c.getContentElement("dictionaries","dictionaryName").setValue(d.getUserDictionaryName()):c.getContentElement("dictionaries","dictionaryName").setValue("")});var p={title:f.getLocal("text_title"),resizable:CKEDITOR.DIALOG_RESIZE_BOTH,minWidth:340,minHeight:260,onLoad:function(){if(0!=c.config.scayt_uiTabs[1]){var a=p,b=a.getLangBoxes.call(this);b.getParent().setStyle("white-space","normal");a.renderLangList(b);this.definition.minWidth=this.getSize().width;this.resize(this.definition.minWidth,
this.definition.minHeight)}},onCancel:function(){n.reset()},onHide:function(){c.unlockSelection()},onShow:function(){c.fire("scaytDialogShown",this);if(0!=c.config.scayt_uiTabs[2]){var a=c.scayt,b=this.getContentElement("dictionaries","dictionaryName"),g=this.getContentElement("dictionaries","existDic").getElement().getParent(),e=this.getContentElement("dictionaries","notExistDic").getElement().getParent();g.hide();e.hide();null!=a.getUserDictionaryName()&&""!=a.getUserDictionaryName()?(this.getContentElement("dictionaries",
"dictionaryName").setValue(a.getUserDictionaryName()),g.show()):(b.setValue(""),e.show())}},onOk:function(){var a=p,b=c.scayt;this.getContentElement("options","scaytOptions");a=a.getChangedOption.call(this);b.commitOption({changedOptions:a})},toggleDictionaryButtons:function(a){var b=this.getContentElement("dictionaries","existDic").getElement().getParent(),c=this.getContentElement("dictionaries","notExistDic").getElement().getParent();a?(b.show(),c.hide()):(b.hide(),c.show())},getChangedOption:function(){var a=
{};if(1==c.config.scayt_uiTabs[0])for(var b=this.getContentElement("options","scaytOptions").getChild(),g=0;g<b.length;g++)b[g].isChanged()&&(a[b[g].id]=b[g].getValue());n.isChanged()&&(a[n.id]=c.config.scayt_sLang=n.currentLang=n.newLang);return a},buildRadioInputs:function(a,b,g){var e=new CKEDITOR.dom.element("div"),d="scaytLang_"+c.name+"_"+b,f=CKEDITOR.dom.element.createFromHtml('\x3cinput id\x3d"'+d+'" type\x3d"radio"  value\x3d"'+b+'" name\x3d"scayt_lang" /\x3e'),m=new CKEDITOR.dom.element("label"),
k=c.scayt;e.setStyles({"white-space":"normal",position:"relative","padding-bottom":"2px"});f.on("click",function(a){n.newLang=a.sender.getValue()});m.appendText(a);m.setAttribute("for",d);g&&c.config.grayt_autoStartup&&m.setStyles({color:"#02b620"});e.append(f);e.append(m);b===k.getLang()&&(f.setAttribute("checked",!0),f.setAttribute("defaultChecked","defaultChecked"));return e},renderLangList:function(a){var b=a.find("#left-col-"+c.name).getItem(0);a=a.find("#right-col-"+c.name).getItem(0);var g=
f.getScaytLangList(),e=f.getGraytLangList(),d={},l=[],m=0,k=!1,h;for(h in g.ltr)d[h]=g.ltr[h];for(h in g.rtl)d[h]=g.rtl[h];for(h in d)l.push([h,d[h]]);l.sort(function(a,b){var c=0;a[1]>b[1]?c=1:a[1]<b[1]&&(c=-1);return c});d={};for(k=0;k<l.length;k++)d[l[k][0]]=l[k][1];l=Math.round(l.length/2);for(h in d)m++,k=h in e.ltr||h in e.rtl,this.buildRadioInputs(d[h],h,k).appendTo(m<=l?b:a)},getLangBoxes:function(){return this.getContentElement("langs","langBox").getElement()},contents:function(a,b){var c=
[],e=b.config.scayt_uiTabs;if(e){for(var d in e)1==e[d]&&c.push(a[d]);c.push(a[a.length-1])}else return a;return c}(q,c)};return p}); B 
