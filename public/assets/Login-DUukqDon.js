import { T } from "./Input-CjMYJgEv.js";
import { T as L } from "./Label-C2KmBUfM.js";
import { T as V } from "./Button-CYWIMkWL.js";
import { T as x } from "./Checkbox-DOKYhsNo.js";
import { L as B } from "./LoadingButton-DuL9lXrz.js";
import { T as C } from "./ValidationErrors-aDX6t04t.js";
import { T as N } from "./AuthenticationCard-BiFLjIHG.js";
import {
    _ as q,
    r as s,
    d as f,
    e as m,
    o as c,
    g as r,
    c as A,
    t as l,
    b as _,
    a,
    h as b,
    w as P
} from "./app-PFKfXWEI.js";

const R = {
    components: {
        TecLabel: L,
        TecInput: T,
        TecButton: V,
        TecCheckbox: x,
        LoadingButton: B,
        TecValidationErrors: C,
        TecAuthenticationCard: N
    },
    props: {
        canResetPassword: Boolean,
        status: String
    },
    created() {
        document.title = this.$t("Login") + " • " + this.$settings.name;
    },
    data() {
        return {
            form: this.$inertia.form({
                username: this.$page.props.demo == 1 ? "admin" : "",
                password: this.$page.props.demo == 1 ? "amI$tup!D?n0" : "",
                remember: !1
            })
        };
    },
    mounted() {
        document.body.classList.add("spinner-normal");
    },
    unmounted() {
        document.body.classList.remove("spinner-normal");
    },
    methods: {
        submit() {
            this.form.transform(e => ({
                ...e,
                remember: this.form.remember ? "on" : ""
            })).post(this.route("login"), {
                onFinish: () => this.form.reset("password")
            });
        }
    }
};

const S = { key: 0, class: "mb-4 font-medium text-green-600" };
const D = { class: "mt-4" };
const E = { class: "block mt-4" };
const F = { class: "flex items-center" };
const I = { class: "ml-2 text-gray-600" };
const J = { class: "w-full mt-4" };

function $(e, o, i, M, t, d) {
    const g = s("tec-validation-errors"),
        u = s("tec-label"),
        p = s("tec-input"),
        w = s("tec-checkbox"),
        k = s("loading-button"),
        v = s("Link"),
        y = s("tec-authentication-card");

    return c(), f(y, null, {
        // Modified logo section starts here
        logo: m(() => [
            a("img", {
                src: "https://portal.alphasal.com/logos/images/cvOt0lWV2K7eDYvtCb2msfB3LCS6FySNYeeueGwX.png",
                class: "h-16 w-auto mx-auto mb-4",
                alt: "Company Logo"
            })
        ]),
        // Modified logo section ends here
        
        default: m(() => [
            r(g, { class: "mb-4" }),
            i.status ? (c(), A("div", S, l(i.status), 1)) : _("", !0),
            a("form", {
                onSubmit: o[3] || (o[3] = P((...n) => d.submit && d.submit(...n), ["prevent"]))
            }, [
                a("div", null, [
                    r(u, {
                        for: "username",
                        value: e.$t("Username")
                    }, null, 8, ["value"]),
                    r(p, {
                        id: "username",
                        type: "text",
                        class: "mt-1 block w-full",
                        modelValue: t.form.username,
                        "onUpdate:modelValue": o[0] || (o[0] = n => t.form.username = n),
                        required: "",
                        autofocus: ""
                    }, null, 8, ["modelValue"])
                ]),
                a("div", D, [
                    r(u, {
                        for: "password",
                        value: e.$t("Password")
                    }, null, 8, ["value"]),
                    r(p, {
                        required: "",
                        id: "password",
                        type: "password",
                        modelValue: t.form.password,
                        "onUpdate:modelValue": o[1] || (o[1] = n => t.form.password = n),
                        class: "mt-1 block w-full",
                        autocomplete: "current-password"
                    }, null, 8, ["modelValue"])
                ]),
                a("div", E, [
                    a("label", F, [
                        r(w, {
                            name: "remember",
                            checked: t.form.remember,
                            "onUpdate:checked": o[2] || (o[2] = n => t.form.remember = n)
                        }, null, 8, ["checked"]),
                        a("span", I, l(e.$t("Remember me")), 1)
                    ])
                ]),
                a("div", J, [
                    r(k, {
                        type: "submit",
                        class: "block w-full",
                        loading: t.form.processing,
                        disabled: t.form.processing
                    }, {
                        default: m(() => [b(l(e.$t("Login")), 1)]),
                        _: 1
                    }, 8, ["loading", "disabled"]),
                    i.canResetPassword ? (c(), f(v, {
                        key: 0,
                        href: e.route("password.request"),
                        class: "transition-all block w-full mt-4 pr-4 py-2 rounded-md hover:pl-4 hover:bg-gray-100 text-gray-600 hover:text-gray-900"
                    }, {
                        default: m(() => [b(l(e.$t("Forgot your password?")), 1)]),
                        _: 1
                    }, 8, ["href"])) : _("", !0)
                ])
            ], 32)
        ]),
        _: 1
    });
}

const Z = q(R, [
    ["render", $]
]);

export { Z as default };