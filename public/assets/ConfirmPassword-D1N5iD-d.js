import{T as h}from"./Input-CjMYJgEv.js";import{T as g}from"./Label-C2KmBUfM.js";import{L as b}from"./LoadingButton-DuL9lXrz.js";import{T as w}from"./ValidationErrors-aDX6t04t.js";import{T}from"./AuthenticationCard-BiFLjIHG.js";import{J as v}from"./AuthenticationCardLogo-uWYAO1p6.js";import{_ as C,r as o,d as V,e as i,o as y,g as t,a as r,t as m,h as L,w as x}from"./app-PFKfXWEI.js";import"./ApplicationMark-CKHzDXqr.js";const B={components:{TecInput:h,TecLabel:g,LoadingButton:b,TecValidationErrors:w,TecAuthenticationCard:T,TecAuthenticationCardLogo:v},data(){return{form:this.$inertia.form({password:""})}},created(){document.title=this.$t("Confirm Password")+" • "+this.$settings.name},methods:{submit(){this.form.post(this.route("password.confirm"),{onFinish:()=>this.form.reset()})}}},P={class:"mb-4 text-gray-600"},$={class:"flex justify-end mt-4"};function k(a,e,A,N,s,c){const d=o("tec-authentication-card-logo"),l=o("tec-validation-errors"),u=o("tec-label"),p=o("tec-input"),f=o("loading-button"),_=o("tec-authentication-card");return y(),V(_,null,{logo:i(()=>[t(d)]),default:i(()=>[r("div",P,m(a.$t("This is a secure area of the application. Please confirm your password before continuing.")),1),t(l,{class:"mb-4"}),r("form",{onSubmit:e[1]||(e[1]=x((...n)=>c.submit&&c.submit(...n),["prevent"]))},[r("div",null,[t(u,{for:"password",value:a.$t("Password")},null,8,["value"]),t(p,{required:"",autofocus:"",id:"password",type:"password",modelValue:s.form.password,"onUpdate:modelValue":e[0]||(e[0]=n=>s.form.password=n),class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"])]),r("div",$,[t(f,{type:"submit",class:"ml-4",loading:s.form.processing,disabled:s.form.processing},{default:i(()=>[L(m(a.$t("Confirm")),1)]),_:1},8,["loading","disabled"])])],32)]),_:1})}const M=C(B,[["render",k]]);export{M as default};
