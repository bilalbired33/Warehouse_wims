import{_ as s,v as d,x as a,o as n,c as u}from"./app-PFKfXWEI.js";const i={emits:["update:checked"],props:{checked:{type:[Array,Boolean],default:!1},value:{default:null}},computed:{proxyChecked:{get(){return this.checked},set(e){this.$emit("update:checked",e)}}}},l=["value"];function p(e,o,t,h,f,c){return d((n(),u("input",{type:"checkbox",value:t.value,"onUpdate:modelValue":o[0]||(o[0]=r=>c.proxyChecked=r),class:"rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"},null,8,l)),[[a,c.proxyChecked]])}const x=s(i,[["render",p]]);export{x as T};
