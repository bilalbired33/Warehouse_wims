import x from"./Details-CnD0QDUH.js";import{A as y}from"./AdminLayout-CAkQ7Os4.js";import{T as g}from"./SectionTitle-B7yJArs8.js";import{_ as b,r as n,d as r,e as o,o as a,a as t,g as c,h as l,t as m,b as u}from"./app-PFKfXWEI.js";import"./JsBarcode-FqNbiSNK.js";import"./Modal-c7b3PsDt.js";import"./TrashedMessage-CoPSjYpa.js";import"./ApplicationLogo-C_9BxMDe.js";import"./ApplicationMark-CKHzDXqr.js";import"./DropdownLink-C4dPXnWr.js";const v={props:{checkin:Object},components:{AdminLayout:y,CheckinDetails:x,TecSectionTitle:g},methods:{print(){window.print()}}},w={class:"px-4 md:px-0"},C={class:"flex items-start justify-between print:hidden"},j={class:"flex items-center"},N={class:"flex"};function T(e,i,s,V,$,f){const d=n("Link"),p=n("tec-section-title"),h=n("icons"),_=n("checkin-details"),k=n("admin-layout");return a(),r(k,{title:e.$t("Checkin Details")},{default:o(()=>[t("div",w,[t("div",C,[c(p,{class:"-mx-4 md:mx-0 mb-6"},{title:o(()=>[t("div",j,[c(d,{class:"text-blue-600 hover:text-blue-700",href:e.route("checkins.index")},{default:o(()=>[l(m(e.$t("Checkins")),1)]),_:1},8,["href"]),i[1]||(i[1]=t("span",{class:"text-blue-600 font-medium mx-2"},"/",-1)),l(" "+m(s.checkin.reference),1)])]),description:o(()=>[l(m(e.$t("Please review the checkin details below")),1)]),_:1}),t("div",N,[t("button",{onClick:i[0]||(i[0]=B=>f.print()),class:"flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none"},[c(h,{name:"printer",class:"h-5 w-5"})]),e.$can("update-checkins")?(a(),r(d,{key:0,href:e.route("checkins.edit",s.checkin.id),class:"flex items-center justify-center mr-2 h-8 w-8 rounded-full text-gray-600 hover:text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-none"},{default:o(()=>[c(h,{name:"edit",class:"h-5 w-5"})]),_:1},8,["href"])):u("",!0)])]),s.checkin?(a(),r(_,{key:0,checkin:s.checkin,class:"mt-0 pt-0"},null,8,["checkin"])):u("",!0)])]),_:1},8,["title"])}const G=b(v,[["render",T]]);export{G as default};
