$(function()
{
   var win = $(window)
      , nav = $('.navbar')
      , navHeight = $('.navbar').first().height()
      , navTop = $('.navbar').length && $('.navbar').offset().top - navHeight
      , isFixed = 0

    processScroll()

    win.on('scroll', processScroll)

    function processScroll()
    {
      var i, scrollTop = win.scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        nav.addClass('navbar-fixed-top')
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        nav.removeClass('navbar-fixed-top')
      }
    }
});
