const slides = document.querySelectorAll(".slides img");
let slideindex=0;
let intervalid= null;
document.addEventListener("DOMContentLoaded",interlizeslide);
function interlizeslide(){
  if(slides.length>0){
    slides[slideindex].classList.add("displaySlide");
    intervalid=setInterval(nextSlide,500);
  }
}
function showslideindex(index){
    if(index>=slides.length){
        slideindex=0;
    }
    else if(index<0){
        slideindex = slides.length - 1;
    }
   radioButton.forEach(slider=>{slider.classList.remove("displySlide")})
    slides.forEach(slider => {slider.classList.remove("displaySlide")});
    slides[slideindex].classList.add("displaySlide")
}
function prevSlide(){
    slideindex--;
    showslideindex(slideindex);
}
function nextSlide(){
    slideindex++;
    showslideindex(slideindex);
    
}