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


  // Thumbnails (horizontal)
  const thumbsSwiper = new Swiper('.product-thumbnail-nav', {
    slidesPerView: 3,
    spaceBetween: 10,
    watchSlidesProgress: true,
    slideToClickedSlide: true
  });

  // Main image
  const mainSwiper = new Swiper('.product-thumb-carousel', {
    slidesPerView: 1,
    effect: 'fade',
    fadeEffect: {
      crossFade: true
    },
    allowTouchMove: false,
    thumbs: {
      swiper: thumbsSwiper
    }
  });




});