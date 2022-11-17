const state = {};
const carouselList = document.querySelector('.carousel__list');
const carouselItems = document.querySelectorAll('.carousel__item');
const elems = Array.from(carouselItems);
const leftarrow = document.querySelector('.left_arrow');
const rightarrow = document.querySelector('.right_arrow');
const overwatch = document.querySelector('.overwatch');
const cityguesser = document.querySelector('.cityguesser');
const rocketleague = document.querySelector('.rocketleague');
const fallguys = document.querySelector('.fallguys');
const mmilan = document.querySelector('.mmilan');
const switchsports = document.querySelector('.switchsports');
const mystere = document.querySelector('.mystere');


carouselList.addEventListener('click', function (event) {
  var newActive = event.target;
  var isItem = newActive.closest('.carousel__item');

  if (!isItem || newActive.classList.contains('carousel__item_active')) {
    return;
  };
  
  update(newActive);
});

leftarrow.addEventListener('click', function (event) {
    
    var newActive = elems.find((elem) => elem.dataset.pos == -1);
    update(newActive);
});

rightarrow.addEventListener('click', function (event) {
    
    var newActive = elems.find((elem) => elem.dataset.pos == 1);
    update(newActive);
});

const update = function(newActive) {
  const newActivePos = newActive.dataset.pos;

  const current = elems.find((elem) => elem.dataset.pos == 0);
  const prev = elems.find((elem) => elem.dataset.pos == -1);
  const next = elems.find((elem) => elem.dataset.pos == 1);
  const prev2 = elems.find((elem) => elem.dataset.pos == -2);
  const next2 = elems.find((elem) => elem.dataset.pos == 2);
  const first = elems.find((elem) => elem.dataset.pos == -3);
  const last = elems.find((elem) => elem.dataset.pos == 3);
  
  current.classList.remove('carousel__item_active');
  
  [current, prev, next, prev2, next2, first, last].forEach(item => {
    var itemPos = item.dataset.pos;

    item.dataset.pos = getPos(itemPos, newActivePos)
  });

 if (newActive.dataset.game=="overwatch"){
overwatch.style.display = "flex";
} else{
    overwatch.style.display = "none";
};

if (newActive.dataset.game=="cityguesser"){
  cityguesser.style.display = "flex";
    } else{
      cityguesser.style.display = "none";
    };

    if (newActive.dataset.game=="rocketleague"){
        rocketleague.style.display = "flex";
        } else{
            rocketleague.style.display = "none";
        };

        if (newActive.dataset.game=="fallguys"){
            fallguys.style.display = "flex";
            } else{
                fallguys.style.display = "none";
            };
            
            if (newActive.dataset.game=="switchsports"){
                switchsports.style.display = "flex";
                } else{
                    switchsports.style.display = "none";
                };

                
                if (newActive.dataset.game=="mmilan"){
                  mmilan.style.display = "flex";
                    } else{
                      mmilan.style.display = "none";
                    };

                    if (newActive.dataset.game=="mystere"){
                        mystere.style.display = "flex";
                        } else{
                            mystere.style.display = "none";
                        };
            
        
  };


const getPos = function (current, active) {
  const diff = current - active;

  if (Math.abs(current - active) > 3) {
    return -current
  }

  return diff;
};



