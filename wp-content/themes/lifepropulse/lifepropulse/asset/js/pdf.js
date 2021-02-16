var date = new Date();
var annee   = date.getFullYear();
var mois    = date.getMonth() + 1;
var jour    = date.getDate();
var heure   = date.getHours();
var minute  = date.getMinutes();
var seconde = date.getSeconds();

if(mois <= 9) {mois = '0' + mois;}
if(jour <= 9) {jour = '0' + jour;}
if(heure <= 9) {heure = '0' + heure;}
if(minute <= 9) {minute = '0' + minute;}
if(seconde <= 9) {seconde = '0' + seconde;}

var todayDate = jour + '' + mois + '' + annee + '_' + heure + '' + minute + '' + seconde;

function ExportPdf(){
    kendo.drawing
    .drawDOM("#js_cvToPDF", 
    { 
        paperSize: "A4",
        margin: { top: "1cm", bottom: "1cm", left: "20px", right: "20px" },
        scale: 0.8,
        height: 500
    })
        .then(function(group){
        kendo.drawing.pdf.saveAs(group, 'Life_Propulse_' + todayDate + '.pdf')
    });
}
kendo.pdf.defineFont({
    "DejaVu Sans": "http://cdn.kendostatic.com/2017.2.621/styles/fonts/DejaVu/DejaVuSans.ttf"
});

// Au click sur le bouton, lance la fonction ExportPdf() (telechargement du pdf)
$('#js_downloadPDF').on('click', function(e) {
    e.preventDefault();
    ExportPdf();
});