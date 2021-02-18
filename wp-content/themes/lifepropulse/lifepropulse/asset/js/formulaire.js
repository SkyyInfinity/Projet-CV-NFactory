
const formBtn1 = document.querySelector("#btn-1")

const formBtnPrev2 = document.querySelector("#btn-2-prev")
const formBtnNext2 = document.querySelector("#btn-2-next")

const formBtnPrev3 = document.querySelector("#btn-3-prev")
const formBtnNext3 = document.querySelector("#btn-3-next")

const formBtnPrev4 = document.querySelector("#btn-4-prev")
const formBtnNext4 = document.querySelector("#btn-4-next")

const formBtnPrev5 = document.querySelector("#btn-5-prev")
const formBtnNext5 = document.querySelector("#btn-5-next")

const formBtnPrev6 = document.querySelector("#btn-6-prev")
const formBtn6 = document.querySelector("#btn-6")


  // Button listener of form 1  BTN SUIVANT 1
  formBtn1.addEventListener("click", function(e) {
    gotoNextForm(formBtn1, formBtnNext2, 1, 2)
    e.preventDefault()
  })
  
  // Next button listener of form 2  BTN SUIVANT 2
  formBtnNext2.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext2, formBtnNext3, 2, 3)
    e.preventDefault()
  })
  
  // Previous button listener of form 2 PRECEDANT 2
  formBtnPrev2.addEventListener("click", function(e) {
    gotoNextForm(formBtnPrev2, formBtn1, 2, 1)
    e.preventDefault()
  })
  
  // Next button listener of form 2  BTN SUIVANT 3
  formBtnNext3.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext3, formBtnNext4, 3, 4)
    e.preventDefault()
  })

  // Previous button listener of form 2 PRECEDANT 3
  formBtnPrev3.addEventListener("click", function(e) {
    gotoNextForm(formBtnPrev3, formBtnPrev2, 3, 2)
    e.preventDefault()
  })

  // Next button listener of form 2  BTN SUIVANT 4
  formBtnNext4.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext4, formBtnNext5, 4, 5)
    e.preventDefault()
  })

  // Previous button listener of form 2 PRECEDANT 4
  formBtnPrev4.addEventListener("click", function(e) {
    gotoNextForm(formBtnPrev4, formBtnPrev3, 4, 3)
    e.preventDefault()
  })

  // Next button listener of form 2  BTN SUIVANT 5
  formBtnNext5.addEventListener("click", function(e) {
    gotoNextForm(formBtnNext5, formBtn6, 5, 6)
    e.preventDefault()
  })

  // Previous button listener of form 2 PRECEDANT 5
  formBtnPrev5.addEventListener("click", function(e) {
    gotoNextForm(formBtnPrev5, formBtnPrev4, 5, 4)
    e.preventDefault()
  })

  // Button listener of form 3  BTN ENVOYEZ 6
  formBtn6.addEventListener("click", function(e) {
    document.querySelector(`.step--5`).classList.remove("step-active")
    document.querySelector(`.step--6`).classList.add("step-active")
    formBtn6.parentElement.style.display = "none"
    document.querySelector(".form--message").innerHTML = `
     <h1 class="form--message-text">Votre CV a bien etait enregistrer.</h1>
     `
    e.preventDefault()
  })

  // Previous button listener of form 2 PRECEDANT 6
  formBtnPrev6.addEventListener("click", function(e) {
    gotoNextForm(formBtnPrev6, formBtnPrev5, 6, 5)
    e.preventDefault()
  })

  const gotoNextForm = (prev, next, stepPrev, stepNext) => {
    // console.log('init');
    // Get form through the button
    const prevForm = prev.parentElement
    const nextForm = next.parentElement
    const nextStep = document.querySelector(`.step--${stepNext}`)
    const prevStep = document.querySelector(`.step--${stepPrev}`)
    // Add active/inactive classes to both previous and next form
    nextForm.classList.add("form-active")
    nextForm.classList.add("form-active-animate")
    prevForm.classList.add("form-inactive")
    // Change the active step element
    prevStep.classList.remove("step-active")
    nextStep.classList.add("step-active")
    // Remove active/inactive classes to both previous an next form
    setTimeout(() => {
      prevForm.classList.remove("form-active")
      prevForm.classList.remove("form-inactive")
      nextForm.classList.remove("form-active-animate")
    }, 1000)
  }

// console.log('coucou2');