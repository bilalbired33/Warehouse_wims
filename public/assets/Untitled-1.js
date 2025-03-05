import { M as F } from "./Modal-c7b3PsDt.js";
import { _ as C, L as N, r as d, o as s, d as h, e as r, a as t, t as u, g as c, b as f, c as a, F as k, i as M, n as v, T as L, I as S, h as b, w as P, f as A, J as T, K as B } from "./app-PFKfXWEI.js";
import { T as D, a as R } from "./DropdownLink-C4dPXnWr.js";
import { T as E } from "./ApplicationMark-CKHzDXqr.js";

// ... keep all other imports and constants the same ...

const he = {
    emits: ["on-mobile-menu-change"],
    components: {
        IconMenus: U,
        TecDropdown: D,
        MobileMenuIcon: H,
        TecDropdownLink: R,
        TecApplicationMark: E
    },
    data() {
        return {
            show: !1,
            languages: S
        }
    },
    // ... keep other options the same ...
};

const pe = { class: "md:flex md:shrink-0" };
const fe = { key: 0, class: "bg-gray-900 md:shrink-0 md:w-16 px-6 py-3 flex items-center justify-between md:justify-center" };
const ge = { key: 1, class: "bg-gray-900 md:shrink-0 md:w-64 px-6 py-3 flex items-center justify-between md:justify-center" };

function Me(e, i, g, y, o, m) {
    const p = d("tec-application-mark"),
        _ = d("Link"),
        $ = d("mobile-menu-icon"),
        // REMOVED: w = d("tec-application-logo"),
        n = d("tec-dropdown-link"),
        l = d("tec-dropdown"),
        j = d("icons"),
        W = d("icon-menus");
        
    return s(), a("div", pe, [
        e.$page.props.settings.sidebar == "mini" ? 
            (s(), a("div", fe, [
                c(_, {
                    class: "text-gray-100",
                    href: e.route("dashboard")
                }, {
                    default: r(() => [
                        // REPLACED APPLICATION MARK WITH CUSTOM LOGO
                        t("img", {
                            src: "https://portal.alphasal.com/logos/images/cvOt0lWV2K7eDYvtCb2msfB3LCS6FySNYeeueGwX.png",
                            class: "block h-8 w-auto",
                            alt: "Company Logo"
                        })
                    ]),
                    _: 1
                }, 8, ["href"]),
                c($, { onOnChange: i[0] || (i[0] = x => o.show = x) })
            ])) : 
            (s(), a("div", ge, [
                c(_, {
                    class: "text-gray-100",
                    href: e.route("dashboard")
                }, {
                    default: r(() => [
                        // REPLACED APPLICATION LOGO WITH CUSTOM LOGO
                        t("img", {
                            src: "https://portal.alphasal.com/logos/images/cvOt0lWV2K7eDYvtCb2msfB3LCS6FySNYeeueGwX.png",
                            class: "block w-56 h-auto",
                            alt: "Company Logo"
                        })
                    ]),
                    _: 1
                }, 8, ["href"]),
                c($, { onOnChange: i[1] || (i[1] = x => o.show = x) })
            ])),
        
        // ... rest of the original template code ...
    ]);
}

// ... keep all subsequent code the same ...