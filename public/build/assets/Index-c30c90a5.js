import{C as k}from"./CheckBox-4666b4b0.js";import{T as U}from"./TextInput-df42c091.js";import{S}from"./SelectInput-e6e66a62.js";import{A as T}from"./AdminLayout-e87dd638.js";import{A as $}from"./AutoComplete-db11f07b.js";import{L as D}from"./LoadingButton-1a262643.js";import{T as C}from"./FormSection-07a3c25c.js";import{T as I}from"./SectionTitle-8b846035.js";import{T as L}from"./ActionMessage-e7228db2.js";import{_ as N,r as n,o as c,d as A,e as a,g as t,h as d,t as i,a as o,T as g,c as f,b}from"./app-32ed258c.js";import"./Checkbox-36df9387.js";import"./InputError-8d3cfe49.js";import"./v4-4a60fe23.js";import"./Label-fda955a2.js";import"./Input-b786da58.js";import"./Modal-291f355a.js";import"./DropdownLink-b7a93580.js";import"./ApplicationLogo-67ce1691.js";import"./ApplicationMark-984b256c.js";import"./throttle-50742131.js";const B={props:["current"],components:{CheckBox:k,TextInput:U,SelectInput:S,AdminLayout:T,AutoComplete:$,LoadingButton:D,TecFormSection:C,TecSectionTitle:I,TecActionMessage:L},data(){return{form:this.$inertia.form({_method:"POST",name:this.current.name,sidebar:this.current.sidebar,language:this.current.language,fraction:+this.current.fraction,per_page:+this.current.per_page,weight_unit:this.current.weight_unit,currency_code:this.current.currency_code,sidebar_style:this.current.sidebar_style,default_locale:this.current.default_locale,reference:this.current.reference||"ulid",over_selling:this.current.over_selling==1,track_weight:this.current.track_weight==1})}},methods:{submit(){this.form.post(route("settings.store"),{preserveScroll:!0})}}},q={class:"flex items-start flex-wrap -mr-4 md:-mr-6"},F={class:"w-full sm:w-1/2"},P={class:"mr-6 mb-6"},E={class:"w-full sm:w-1/2"},M={class:"mr-6 mb-6"},R={class:"w-full sm:w-1/2"},G={class:"mr-6 mb-6"},H={class:"w-full sm:w-1/2"},O={class:"mr-6 mb-6"},Q={class:"w-full sm:w-1/2"},W={class:"mr-6 mb-6"},Y={class:"w-full sm:w-1/2"},j={class:"mr-6 mb-6"},z={class:"w-full sm:w-1/2"},J={class:"mr-6 mb-6"},K={class:"w-full sm:w-1/2"},X={class:"mr-6 mb-6"},Z={key:0,class:"w-full sm:w-1/2"},x={class:"mr-6 mb-6"},ee={class:"w-full mb-4"},oe={class:"w-full"},le={key:0,class:"mt-6 w-full sm:w-1/2"},re={class:"mr-6 mb-6"},se={class:"mt-8"},te={class:"mt-6 bg-white shadow overflow-hidden md:rounded-md"},ae={class:"border-gray-200"},ie={class:"bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"},ne={class:"font-medium text-gray-500"},me={class:"mt-1 text-gray-900 sm:mt-0 sm:col-span-2"},de={class:"bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"},ue={class:"font-medium text-gray-500"},ce={class:"mt-1 text-gray-900 sm:mt-0 sm:col-span-2"},pe={class:"bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"},ge={class:"font-medium text-gray-500"},fe={class:"mt-1 text-gray-900 sm:mt-0 sm:col-span-2"};function be(e,r,_e,he,l,_){const u=n("text-input"),m=n("auto-complete"),p=n("check-box"),h=n("tec-action-message"),v=n("loading-button"),w=n("tec-form-section"),y=n("tec-section-title"),V=n("admin-layout");return c(),A(V,{title:e.$t("Settings")},{default:a(()=>[t(w,{onSubmitted:_.submit},{title:a(()=>[d(i(e.$t("Settings")),1)]),description:a(()=>[d(i(e.$t("Please fill the form below to update settings.")),1)]),form:a(()=>[o("div",q,[o("div",F,[o("div",P,[t(u,{modelValue:l.form.name,"onUpdate:modelValue":r[0]||(r[0]=s=>l.form.name=s),error:e.$page.props.errors.name,label:e.$t("Site Name")},null,8,["modelValue","error","label"])])]),o("div",E,[o("div",M,[t(m,{id:"language",searchable:!1,modelValue:l.form.language,"onUpdate:modelValue":r[1]||(r[1]=s=>l.form.language=s),label:e.$t("Language"),error:e.$page.props.errors.language,suggestions:e.$page.props.languages},null,8,["modelValue","label","error","suggestions"])])]),o("div",R,[o("div",G,[t(m,{id:"reference",searchable:!1,modelValue:l.form.reference,"onUpdate:modelValue":r[2]||(r[2]=s=>l.form.reference=s),label:e.$t("Reference"),error:e.$page.props.errors.reference,suggestions:[{value:"ulid",label:"ULID - Universally Unique Lexicographically Sortable Identifier"},{value:"ai",label:"Auto Increment (MYSQL only)"},{value:"uniqid",label:"Uniqid - PHP Generate a Unique ID"},{value:"uuid",label:"UUID - Universally Unique Identifier"}]},null,8,["modelValue","label","error","suggestions"])])]),o("div",H,[o("div",O,[t(u,{modelValue:l.form.currency_code,"onUpdate:modelValue":r[3]||(r[3]=s=>l.form.currency_code=s),error:e.$page.props.errors.currency_code,label:e.$t("Currency Code")},null,8,["modelValue","error","label"])])]),o("div",Q,[o("div",W,[t(u,{modelValue:l.form.default_locale,"onUpdate:modelValue":r[4]||(r[4]=s=>l.form.default_locale=s),error:e.$page.props.errors.default_locale,label:e.$t("Date & Number Locale")},null,8,["modelValue","error","label"])])]),o("div",Y,[o("div",j,[t(m,{id:"fraction",searchable:!1,modelValue:l.form.fraction,"onUpdate:modelValue":r[5]||(r[5]=s=>l.form.fraction=s),label:e.$t("Decimal Fractions"),error:e.$page.props.errors.fraction,suggestions:[{value:0,label:0},{value:1,label:1},{value:2,label:2},{value:3,label:3},{value:4,label:4}]},null,8,["modelValue","label","error"])])]),o("div",z,[o("div",J,[t(m,{id:"per_page",searchable:!1,modelValue:l.form.per_page,"onUpdate:modelValue":r[6]||(r[6]=s=>l.form.per_page=s),label:e.$t("Rows per page"),error:e.$page.props.errors.per_page,suggestions:[{value:10,label:10},{value:15,label:15},{value:25,label:25},{value:50,label:50},{value:100,label:100}]},null,8,["modelValue","label","error"])])]),o("div",K,[o("div",X,[t(m,{id:"sidebar",searchable:!1,label:e.$t("Sidebar"),modelValue:l.form.sidebar,"onUpdate:modelValue":r[7]||(r[7]=s=>l.form.sidebar=s),error:e.$page.props.errors.sidebar,suggestions:[{value:"full",label:e.$t("Default")},{value:"mini",label:e.$t("Collapsed")}]},null,8,["label","modelValue","error","suggestions"])])]),t(g,{name:"slidedown"},{default:a(()=>[l.form.sidebar=="full"?(c(),f("div",Z,[o("div",x,[t(m,{id:"sidebar_style",searchable:!1,label:e.$t("Sidebar Style"),modelValue:l.form.sidebar_style,"onUpdate:modelValue":r[8]||(r[8]=s=>l.form.sidebar_style=s),error:e.$page.props.errors.sidebar_style,suggestions:[{value:"full",label:e.$t("Full")},{value:"dropdown",label:e.$t("Dropdown")}]},null,8,["label","modelValue","error","suggestions"])])])):b("",!0)]),_:1}),o("div",ee,[t(p,{id:"over_selling",label:e.$t("Enable over selling"),checked:l.form.over_selling,"onUpdate:checked":r[9]||(r[9]=s=>l.form.over_selling=s),error:e.$page.props.errors.over_selling},null,8,["label","checked","error"])]),o("div",oe,[t(p,{id:"track_weight",checked:l.form.track_weight,"onUpdate:checked":r[10]||(r[10]=s=>l.form.track_weight=s),error:e.$page.props.errors.track_weight,label:e.$t("Track weight of item")},null,8,["checked","error","label"]),t(g,{name:"slidedown"},{default:a(()=>[l.form.track_weight?(c(),f("div",le,[o("div",re,[t(u,{modelValue:l.form.weight_unit,"onUpdate:modelValue":r[11]||(r[11]=s=>l.form.weight_unit=s),error:e.$page.props.errors.weight_unit,label:e.$t("Weight Unit")},null,8,["modelValue","error","label"])])])):b("",!0)]),_:1})])])]),actions:a(()=>[t(h,{on:l.form.recentlySuccessful,class:"mr-3"},{default:a(()=>[d(" Saved. ")]),_:1},8,["on"]),t(v,{type:"submit",loading:l.form.processing,disabled:l.form.processing},{default:a(()=>[d(" Save ")]),_:1},8,["loading","disabled"])]),_:1},8,["onSubmitted"]),o("div",se,[t(y,null,{title:a(()=>[d(i(e.$t("Preview")),1)]),description:a(()=>[d(i(e.$t("Number and Date Format")),1)]),_:1}),o("div",te,[o("div",ae,[o("dl",null,[o("div",ie,[o("dt",ne,i(e.$t("Number")),1),o("dd",me,i(e.$number(200000005e-1,this.form.default_locale,{minimumFractionDigits:this.form.fraction})),1)]),o("div",de,[o("dt",ue,i(e.$t("Date")),1),o("dd",ce,i(e.$date(new Date,this.form.default_locale)),1)]),o("div",pe,[o("dt",ge,i(e.$t("Date Time")),1),o("dd",fe,i(e.$datetime(new Date,this.form.default_locale)),1)])])])])])]),_:1},8,["title"])}const Me=N(B,[["render",be]]);export{Me as default};
