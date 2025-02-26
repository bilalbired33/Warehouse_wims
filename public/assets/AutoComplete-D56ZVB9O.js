import{t as w}from"./throttle-DnWO0zlu.js";import{T as _}from"./Label-C2KmBUfM.js";import{T as V}from"./InputError-S5X7Xksv.js";import{_ as x,r as d,o as r,c as n,d as b,b as u,a,g as y,v as S,M as C,z as f,n as g,F as p,i as L,t as h}from"./app-PFKfXWEI.js";import{v as T}from"./v4-C6aID195.js";const A={emits:["update:modelValue"],components:{TecLabel:_,TecInputError:V},data(){return{search:"",result:[],open:!1,current:null,loading:!1,selected:null}},props:{id:{type:String,default(){return T()}},json:Boolean,label:String,error:String,position:String,valueKey:String,labelKey:String,defaultText:String,placeholder:String,disable:[String,Number],modelValue:[String,Number],suggestions:[String,Array],clearable:{type:Boolean,default:!1},searchable:{type:Boolean,default:!0},keepFocus:{type:Boolean,default:!1},resetSearch:{type:Boolean,default:!1}},watch:{modelValue:function(e){e||(this.search="",this.$nextTick(()=>this.open=!1))},search:function(e){this.searchable&&(this.open=!0,Array.isArray(this.suggestions)?this.result=e?this.suggestions.filter(t=>this.cLabel(t,e)):this.suggestions:(this.loading=!0,this.getSuggestions(e)))}},computed:{currentLabel(){return this.cLabel(this.selected)},currentValue(){return this.cValue(this.selected)}},created(){Array.isArray(this.suggestions)&&(this.result=this.suggestions)},mounted(){this.modelValue&&(Array.isArray(this.suggestions)?(this.selected=this.suggestions.find(e=>e[this.valueKey]==this.modelValue||e.value==this.modelValue||e.id==this.modelValue),this.search=this.currentLabel,this.$nextTick(()=>this.open=!1)):axios.post(this.suggestions,{id:this.modelValue}).then(e=>{this.result=e.data,this.selected=e.data[0],this.search=this.cLabel(this.selected)}).finally(()=>{this.open=!1,this.loading=!1}))},methods:{getSuggestions:w(function(e){(e&&!this.result||this.result&&(!this.result.length||this.modelValue!=this.result[0].id))&&axios.post(this.suggestions,{search:e}).then(t=>{this.result=t.data,this.result.length==1&&this.suggestionClick(0,!0)}).finally(()=>this.loading=!1)},250),cLabel(e,t){if(e===null)return null;if(typeof e!="object")return e;let s="";return this.labelKey?s=this.labelKey:e.label!==void 0?s="label":e.name!==void 0&&(s="name"),t?s?e[s].toLowerCase().includes(t.toLowerCase()):e.toLowerCase().includes(t.toLowerCase()):s?e[s]:e},cValue(e){if(e===null)return null;if(typeof e!="object")return e;let t="";return this.valueKey?t=this.valueKey:e.value!==void 0?t="value":e.id!==void 0&&(t="id"),t?e[t]:e},show(){this.open=!0,this.search="",this.$refs.input.select(),this.selected||(this.current=0)},hide(){setTimeout(()=>{this.selected&&(this.search=this.currentLabel),setTimeout(()=>this.open=!1,100)},200)},enter(){return this.suggestionClick(this.current),this.hide(),!1},up(){this.current>0&&this.current--},down(){this.current<this.result.length-1&&this.current++},isActive(e){return e==this.current},suggestionClick(e,t=!1){e!==void 0&&(this.current=e,this.selected=this.result[e],this.$emit("update:modelValue",this.json?this.selected:this.currentValue),this.search=this.resetSearch?"":this.currentLabel,this.open=!1),t&&(this.result=null),this.resetSearch&&(this.selected=null),this.keepFocus&&this.$refs.input.focus()},reset(){this.search="",this.$emit("update:modelValue",""),this.$nextTick(()=>this.open=!1)}}},B={class:"col-span-6 sm:col-span-4"},K={class:"relative mt-1 mb-2"},N=["for"],j=["id","readonly","placeholder"],F={class:"max-h-56 rounded-md bg-white shadow-lg text-base overflow-auto focus:outline-none sm:text-sm"},z={key:0},D=["onClick"],E={class:"ml-3 block font-normal truncate"},M={key:1,class:"bg-blue-600 text-white cursor-default select-none relative py-2 pl-3 pr-9"},I={class:"flex items-center"},P={class:"ml-3 block font-normal truncate"},U={key:0},q={key:1},G={key:2},H={key:3};function J(e,t,s,O,o,l){const k=d("tec-label"),m=d("icons"),v=d("tec-input-error");return r(),n("div",B,[s.label?(r(),b(k,{key:0,for:s.id,value:s.label},null,8,["for","value"])):u("",!0),a("div",K,[a("label",{for:s.id,name:"chevron-down",class:"cursor-pointer absolute top-0 right-0 border border-transparent p-2 text-gray-500"},[y(m,{name:"chevron-down","class-name":"w-5 h-6"})],8,N),s.clearable&&s.modelValue?(r(),n("button",{key:0,type:"button",onClick:t[0]||(t[0]=i=>l.reset()),class:"cursor-pointer focus:outline-none absolute top-0 right-8 border border-transparent p-2 text-gray-500 hover:text-red-500"},[y(m,{name:"cross","class-name":"w-4 h-6"})])):u("",!0),S(a("input",{id:s.id,ref:"input",onBlur:t[1]||(t[1]=(...i)=>l.hide&&l.hide(...i)),onFocus:t[2]||(t[2]=(...i)=>l.show&&l.show(...i)),onClick:t[3]||(t[3]=(...i)=>l.show&&l.show(...i)),onKeyup:[t[4]||(t[4]=f((...i)=>l.up&&l.up(...i),["up"])),t[6]||(t[6]=f((...i)=>l.down&&l.down(...i),["down"])),t[7]||(t[7]=f((...i)=>l.enter&&l.enter(...i),["enter"]))],"onUpdate:modelValue":t[5]||(t[5]=i=>o.search=i),autocomplete:"off",readonly:!s.searchable,placeholder:s.placeholder||s.label,class:g([{"border-red-500":s.error,"pr-8":!s.clearable,"pr-16":s.clearable},"w-full block text-base py-2 pl-4 placeholder-gray-400 border border-gray-300 focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"])},null,42,j),[[C,o.search]]),o.open?(r(),n("div",{key:1,class:g([s.position,"absolute mt-2 w-full rounded-md z-20"])},[a("ul",F,[o.result&&o.result.length?(r(!0),n(p,{key:0},L(o.result,(i,c)=>(r(),n(p,{key:c},[s.disable&&s.disable==l.cValue()?u("",!0):(r(),n("li",z,[a("button",{type:"button",onClick:Q=>l.suggestionClick(c),class:g([l.isActive(c)?"bg-blue-600 text-white":"bg-white text-gray-900","w-full text-left flex items-center select-none relative py-2 pl-3 pr-9 hover:bg-blue-600 hover:text-white"])},[a("span",E,h(l.cLabel(i)),1)],10,D)]))],64))),128)):(r(),n("li",M,[a("div",I,[a("span",P,[o.loading?(r(),n("span",U,h(e.$t("Searching for results"))+"...",1)):o.result==null?(r(),n("span",q,h(e.$t("Scan barcode or search items for next")),1)):s.json?(r(),n("span",G,h(s.defaultText||e.$t("Please type to search")),1)):(r(),n("span",H,h(e.$t("No result suggestions to list.")),1))])])]))])],2)):u("",!0),s.error?(r(),b(v,{key:2,message:s.error,class:"absolute mt-0"},null,8,["message"])):u("",!0)])])}const $=x(A,[["render",J]]);export{$ as A};
