const mixin = {
  computed: {
    $user() {
      return this.$page.props.auth.user;
    },
    $super() {
      return this.$page.props.auth.user.roles.find(r => r.name == 'Super Admin') ? true : false;
    },
    $settings() {
      return { ...this.$page.props.settings, track_weight: this.$page.props.settings.track_weight == 1 };
    },
  },
  methods: {
    $capitalize(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    $number(amount, locale, options) {
      let formatted = parseFloat(amount);
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      if (!options) {
        options = { minimumFractionDigits: this.$page.props.settings.fraction, maximumFractionDigits: this.$page.props.settings.fraction };
      }
      return new Intl.NumberFormat(locale, options).format(formatted);
    },
    $currency(amount, locale, options) {
      let formatted = parseFloat(amount);
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      if (options.currency && options.currency.length != 3) {
        options.currency = this.$page.props.settings.currency_code;
      }
      if (!options) {
        options = {
          style: 'currency',
          signDisplay: 'always',
          currencyDisplay: 'symbol',
          // currencySign: 'accounting',
          currency: this.$page.props.settings.currency_code,
          minimumFractionDigits: this.$page.props.settings.fraction,
        };
      }
      return new Intl.NumberFormat(locale, options).format(formatted);
    },
    $parseNumber(amount, locale) {
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      var thousandSeparator = Intl.NumberFormat(locale)
        .format(11111)
        .replace(/\p{Number}/gu, '');
      var decimalSeparator = Intl.NumberFormat(locale)
        .format(1.1)
        .replace(/\p{Number}/gu, '');
      return parseFloat(amount.replace(new RegExp('\\' + thousandSeparator, 'g'), '').replace(new RegExp('\\' + decimalSeparator), '.'));
    },
    $date(date, locale, style) {
      if (this.$page.props.settings?.date_format == 'php') {
        return date.toString().split('T')[0];
      }

      let formatted = new Date(Date.parse(date));
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      return formatted.toLocaleString(locale, { dateStyle: style ? style : 'medium' });
    },
    $formatJSDate(date) {
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
      if (month.length < 2) month = '0' + month;
      if (day.length < 2) day = '0' + day;
      return [year, month, day].join('-');
    },
    $dateDay(date, locale) {
      let formatted = new Date(Date.parse(date));
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      return formatted.toLocaleString(locale, { day: 'numeric', weekday: 'short' });
    },
    $month(month, locale, style = 'short') {
      let formatted = new Date(Date.parse(month));
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      return formatted.toLocaleString(locale, { month: style, year: '2-digit' });
    },
    $monthName(month, locale, style = 'long') {
      let formatted = new Date(Date.parse(month));
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      return formatted.toLocaleString(locale, { month: style });
    },
    $time(date, locale, style) {
      let formatted = new Date(Date.parse(date));
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      return formatted.toLocaleString(locale, { timeStyle: 'short', hour12: true });
    },
    $datetime(datetime, locale, style) {
      if (!datetime) {
        return '';
      }
      if (this.$page.props.settings?.date_format == 'php') {
        return datetime;
      }

      let formatted = new Date(Date.parse(datetime));
      if (!locale || (locale.length != 2 && locale.length != 5)) {
        locale = this.$page.props.settings.default_locale;
      }
      return formatted.toLocaleString(locale, { dateStyle: style ? style : 'medium', timeStyle: 'short', hour12: true });
    },
    $can(permissions) {
      if (this.$page.props.auth.user && this.$page.props.auth.user.roles.find(r => r.name == 'Super Admin')) {
        return true;
      }
      let allow = false;
      if (!Array.isArray(permissions)) {
        permissions = [permissions];
      }
      if (permissions && permissions.length > 0) {
        if (permissions.includes('all')) {
          allow = true;
        } else {
          permissions.map(p => {
            if (
              this.$page.props.auth.user &&
              this.$page.props.auth.user.all_permissions &&
              this.$page.props.auth.user.all_permissions.includes(p)
            ) {
              allow = true;
            }
          });
        }
      }
      return allow;
    },
    $meta(meta, noHtml = false, join = false) {
      let str = [];
      for (const [key, value] of Object.entries(meta)) {
        noHtml ? str.push(key + ': ' + value) : str.push(key + ': <strong>' + value + '</strong>');
      }
      return str.join(join ? '\n ' : ', ');
    },
  },
};

export default mixin;
