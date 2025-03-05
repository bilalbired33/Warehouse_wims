import { J as v } from "./JsBarcode-FqNbiSNK.js";
import { M as D } from "./Modal-c7b3PsDt.js";
import { T as B } from "./TrashedMessage-CoPSjYpa.js";
import { 
    _ as P,
    r as u,
    c as a,
    a as t,
    g as w,
    t as e,
    b as r,
    d as x,
    e as p,
    n as d,
    F as g,
    i as b,
    o as i,
    h as m
} from "./app-PFKfXWEI.js";
import "./ApplicationMark-CKHzDXqr.js";

const j = {
    props: {
        checkout: Object
    },
    components: {
        Modal: D,
        TrashedMessage: B
    },
    data() {
        return {
            photo: null,
            show: !1,
            loading: !1
        }
    },
    mounted() {
        v("#barcode", this.checkout.reference, {
            width: 1,
            margin: 0,
            fontSize: 0,
            height: "20",
            format: "CODE128"
        })
    },
    updated() {
        v("#barcode", this.checkout.reference, {
            width: 1,
            margin: 0,
            fontSize: 0,
            height: "20",
            format: "CODE128"
        })
    },
    methods: {
        showPhoto(s) {
            this.show = !0, this.photo = "/assets/" + s
        },
        hidePhoto() {
            this.show = !1, this.photo = null
        }
    }
};

// Added logo container style
const logoContainer = {
    class: "mb-6 print:hidden text-center"
};

const L = { key: 0, class: "mt-auto bg-gray-100 -m-6 py-6 px-4 md:px-6 rounded-md print:bg-white print:mt-0 print:pt-0 print:h-screen print:overflow-visible" };
const N = { class: "bg-white p-4 rounded-md shadow overflow-x-auto print:shadow-none print:pt-0" };
const V = { class: "block sm:flex justify-between print:flex" };
const q = { class: "text-left sm:text-right leading-snug max-w-md print:text-right" };
const z = { class: "font-bold" };
const E = { key: 0 };
const F = { key: 1 };
const O = { key: 2 };
const S = { class: "py-4 w-full" };
const A = { class: "text-xl text-center uppercase font-extrabold" };
const H = { class: "block sm:flex justify-between print:flex" };
const J = { class: "w-full sm:w-1/2 leading-snug mb-6 sm:mb-0" };
const I = { class: "text-left w-full sm:w-1/2 leading-snug" };
const Q = { class: "text-sm font-bold" };
const R = { key: 0 };
const W = { key: 1 };
const G = { class: "-mx-4 overflow-x-auto" };
const K = { class: "w-full mt-8 mb-4", style: { "min-width": "500px" } };
const U = { class: "px-6 py-2 text-left" };
const X = { key: 0 };
const Y = { class: "px-6 py-2 w-32 text-right" };
const Z = { class: "group-hover:bg-gray-100 border-t px-6 pt-2 font-bold" };
const tt = { class: "flex items-center gap-x-2 gap-y-1" };
const et = ["src", "onClick"];
const st = { class: "flex items-center gap-x-2 gap-y-1" };
const ot = { key: 0, class: "block w-8 h-8 rounded-sm -my-2" };
const nt = ["innerHTML"];
const it = { key: 0 };
const at = { class: "group-hover:bg-gray-100 border-t px-6 py-2" };
const rt = { class: "flex items-center gap-x-2 gap-y-1" };
const ct = ["src", "onClick"];
const dt = { key: 0 };
const lt = { class: "group-hover:bg-gray-100 border-t px-6 py-2 w-32 text-right" };
const ht = { key: 2, class: "print:hidden py-4 w-full" };
const ut = { class: "font-medium" };
const gt = { class: "mt-1 text-sm text-gray-900" };
const mt = { role: "list", class: "border border-gray-200 rounded-md divide-y divide-gray-200" };
const _t = { class: "w-0 flex-1 flex items-center" };
const kt = { class: "ml-2 flex-1 w-0 truncate" };
const wt = { class: "ml-4 flex-shrink-0" };
const pt = ["href"];
const bt = { key: 3, class: "py-4 w-full" };
const ft = { class: "mt-auto pt-4 w-full text-center text-sm text-gray-500 hidden print:block" };
const yt = { class: "print:block print:h-full" };
const vt = ["src"];

function xt(s, c, n, Ct, f, _) {
    const y = u("trashed-message"),
        T = u("icons"),
        $ = u("modal");

    return n.checkout ? (i(), a("div", L, [
        t("div", N, [
            t("div", V, [
                // REPLACE TEC-APPLICATION-LOGO WITH CUSTOM LOGO HERE
                t("div", { 
                    class: "mb-4 sm:mb-0" 
                }, [
                    t("img", {
                        src: "https://portal.alphasal.com/logos/images/cvOt0lWV2K7eDYvtCb2msfB3LCS6FySNYeeueGwX.png",
                        class: "h-12 w-auto",
                        alt: "Company Logo"
                    })
                ]),
                
                 
                t("div", q, [
                    t("div", z, e(n.checkout.warehouse.name) + " (" + e(n.checkout.warehouse.code) + ")", 1),
                    n.checkout.warehouse.address ? (i(), a("div", E, e(n.checkout.warehouse.address), 1)) : r("", !0),
                    n.checkout.warehouse.phone ? (i(), a("div", F, e(n.checkout.warehouse.phone), 1)) : r("", !0),
                    n.checkout.warehouse.email ? (i(), a("div", O, e(n.checkout.warehouse.email), 1)) : r("", !0)
                ])
            ]),
            c[4] || (c[4] = t("div", { class: "border-b my-4 -mx-4" }, null, -1)),
            n.checkout.draft == 1 ? (i(), x(y, {
                key: 0,
                class: "mb-4 h-12 print:hidden",
                action: !1,
                icon: "info"
            }, {
                default: p(() => [m(e(s.$t("This record is still a draft.")), 1)]),
                _: 1
            })) : r("", !0),
            n.checkout.deleted_at ? (i(), x(y, {
                key: 1,
                class: "mb-4 h-12 print:hidden",
                action: !1
            }, {
                default: p(() => [m(e(s.$t("This record has been deleted.")), 1)]),
                _: 1
            })) : r("", !0),
            t("div", S, [
                t("h1", A, e(s.$t("Checkout")), 1),
                c[1] || (c[1] = t("svg", {
                    id: "barcode",
                    class: "mt-2 mx-auto"
                }, null, -1))
            ]),
            t("div", H, [
                t("div", J, [
                    c[2] || (c[2] = t("div", { class: "text-sm font-bold" }, " ", -1)),
                    t("div", null, e(s.$t("Date")) + ": " + e(s.$date(n.checkout.date)), 1),
                    t("div", null, e(s.$t("Reference")) + ": " + e(n.checkout.reference), 1),
                    t("div", null, e(s.$t("Created at")) + ": " + e(s.$datetime(n.checkout.created_at)), 1)
                ]),
                t("div", I, [
                    t("div", Q, e(s.$t("For")) + ":", 1),
                    t("div", null, e(n.checkout.contact.name), 1),
                    n.checkout.contact.phone ? (i(), a("div", R, e(n.checkout.contact.phone), 1)) : r("", !0),
                    n.checkout.contact.email ? (i(), a("div", W, e(n.checkout.contact.email), 1)) : r("", !0)
                ])
            ]),
            t("div", G, [
                t("table", K, [
                    t("thead", null, [
                        t("tr", null, [
                            t("th", U, e(s.$t("Item")), 1),
                            t("th", {
                                class: d(["px-6 py-2 text-right", s.$settings.track_weight ? "w-32" : "w-px"])
                            }, [s.$settings.track_weight ? (i(), a("span", X, e(s.$t("Weight")), 1)) : r("", !0)], 2),
                            t("th", Y, e(s.$t("Quantity")), 1)
                        ])
                    ]),
                    (i(!0), a(g, null, b(n.checkout.items, (o, h) => (i(), a(g, {
                        key: "i_" + o.id
                    }, [
                        o.variations.length ? (i(), a("tbody", {
                            key: 0,
                            class: d(["group avoid", {
                                "bg-gray-50": h % 2 != 0,
                                "border-b": n.checkout.items.length == h + 1
                            }])
                        }, [
                            t("tr", null, [
                                t("td", Z, [
                                    t("div", tt, [
                                        o.item.photo ? (i(), a("img", {
                                            key: 0,
                                            class: "block w-8 h-8 rounded-sm -my-2",
                                            src: "/assets/" + o.item.photo,
                                            onClick: l => _.showPhoto(o.item.photo)
                                        }, null, 8, et)) : r("", !0),
                                        m(" " + e(s.$t(o.item.name)) + " (" + e(o.item.code) + ") ", 1)
                                    ])
                                ]),
                                t("td", {
                                    class: d(["group-hover:bg-gray-100 border-t px-6 py-2 text-right", s.$settings.track_weight && o.item.track_weight ? "w-32" : "w-px"])
                                }, null, 2),
                                c[3] || (c[3] = t("td", {
                                    class: "group-hover:bg-gray-100 border-t px-6 pt-2 w-32 text-right"
                                }, null, -1))
                            ]),
                            (i(!0), a(g, null, b(o.variations, (l, k) => (i(), a("tr", {
                                key: "v_" + l.id
                            }, [
                                t("td", {
                                    class: d([{
                                        "pb-2": k + 1 == o.variations.length
                                    }, "group-hover:bg-gray-100 px-6 pt-2"])
                                }, [
                                    t("div", st, [
                                        o.item.photo ? (i(), a("span", ot)) : r("", !0),
                                        t("span", {
                                            innerHTML: s.$meta(l.meta)
                                        }, null, 8, nt)
                                    ])
                                ], 2),
                                t("td", {
                                    class: d([{
                                        "pb-2": k + 1 == o.variations.length,
                                        "w-32": s.$settings.track_weight && o.item.track_weight
                                    }, "group-hover:bg-gray-100 px-6 pt-2 text-right"])
                                }, [
                                    s.$settings.track_weight && o.item.track_weight ? (i(), a("span", it, e(s.$number(l.pivot.weight)) + " " + e(s.$settings.weight_unit), 1)) : r("", !0)
                                ], 2),
                                t("td", {
                                    class: d([{
                                        "pb-2": k + 1 == o.variations.length
                                    }, "group-hover:bg-gray-100 px-6 pt-2 w-32 text-right"])
                                }, e(s.$number(l.pivot.quantity)) + " " + e(o.unit ? o.unit.code : ""), 3)
                            ]))), 128))
                        ], 2)) : (i(), a("tbody", {
                            key: 1,
                            class: d(["group avoid", {
                                "bg-gray-50": h % 2 != 0,
                                "border-b": n.checkout.items.length == h + 1
                            }])
                        }, [
                            t("tr", null, [
                                t("td", at, [
                                    t("div", rt, [
                                        o.item.photo ? (i(), a("img", {
                                            key: 0,
                                            class: "block w-8 h-8 rounded-sm -my-2",
                                            src: "/assets/" + o.item.photo,
                                            onClick: l => _.showPhoto(o.item.photo)
                                        }, null, 8, ct)) : r("", !0),
                                        m(" " + e(s.$t(o.item.name)) + " (" + e(o.item.code) + ") ", 1)
                                    ])
                                ]),
                                t("td", {
                                    class: d(["group-hover:bg-gray-100 border-t px-6 py-2 text-right", s.$settings.track_weight && o.item.track_weight ? "w-32" : "w-px"])
                                }, [
                                    s.$settings.track_weight && o.item.track_weight ? (i(), a("span", dt, e(s.$number(o.weight)) + " " + e(s.$settings.weight_unit), 1)) : r("", !0)
                                ], 2),
                                t("td", lt, e(s.$number(o.quantity)) + " " + e(o.unit ? o.unit.code : ""), 1)
                            ])
                        ], 2))
                    ], 64))), 128))
                ])
            ]),
            n.checkout.attachments && n.checkout.attachments.length ? (i(), a("div", ht, [
                t("dt", ut, e(s.$t("Attachments")), 1),
                t("dd", gt, [
                    t("ul", mt, [
                        (i(!0), a(g, null, b(n.checkout.attachments, o => (i(), a("li", {
                            key: o.id,
                            class: "pl-3 pr-4 py-3 flex items-center justify-between text-sm"
                        }, [
                            t("div", _t, [
                                w(T, {
                                    name: "clip",
                                    class: "flex-shrink-0 h-5 w-5 text-gray-400"
                                }),
                                t("span", kt, e(o.title), 1)
                            ]),
                            t("div", wt, [
                                t("a", {
                                    class: "font-medium text-indigo-600 hover:text-indigo-500",
                                    href: s.route("media.download", o.id)
                                }, e(s.$t("Download")), 9, pt)
                            ])
                        ]))), 128))
                    ])
                ])
            ])) : r("", !0),
            n.checkout.details ? (i(), a("div", bt, e(s.$t(n.checkout.details)), 1)) : r("", !0)
        ]),
        t("div", ft, e(s.$t("This is computer generated document, no signature required.")), 1),
        w($, {
            show: f.show,
            "max-width": "2xl",
            closeable: !0,
            transparent: !0,
            onClose: c[0] || (c[0] = o => _.hidePhoto())
        }, {
            default: p(() => [
                t("div", yt, [
                    t("img", {
                        class: "block max-w-full mx-auto rounded-md",
                        src: f.photo
                    }, null, 8, vt)
                ])
            ]),
            _: 1
        }, 8, ["show"])
    ])) : r("", !0)
}

const jt = P(j, [
    ["render", xt]
]);
export { jt as default };