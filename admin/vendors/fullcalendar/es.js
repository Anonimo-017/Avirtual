!(function (e, a) {
  "object" == typeof exports && "object" == typeof module
    ? (module.exports = a(require("fullcalendar")))
    : "function" == typeof define && define.amd
      ? define(["fullcalendar"], a)
      : "object" == typeof exports
        ? (exports.FullCalendarLocales = a(require("fullcalendar")))
        : ((e.FullCalendarLocales = e.FullCalendarLocales || {}),
          (e.FullCalendarLocales.es = a(e.FullCalendar)));
})("undefined" != typeof self ? self : this, function (e) {
  return (
    e.locale("es", {
      buttonText: {
        month: "Mes",
        week: "Semana",
        day: "Día",
        list: "Agenda",
      },
      allDayText: "Todo el día",
      eventLimitText: "más",
      noEventsMessage: "No hay eventos para mostrar",
    }),
    e
  );
});
