document.addEventListener('DOMContentLoaded', function () {

  new Swiper('.posts-slider', {
    slidesPerView: 1.3,
    spaceBetween: 24,
    // loop: true,
    // pagination: {
    //   el: '.slider-pagination',
    //   clickable: true,
    // },
    pagination: {
      el: ".slider-pagination",
      clickable: true,
    },
    breakpoints: {
      1024: {
        slidesPerView: 3,
        spaceBetween: 40,
      },

      576: {
        slidesPerView: 2.4,
        spaceBetween: 40,
      },
    }
  });

});