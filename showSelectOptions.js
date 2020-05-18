function showEnglishSubtopics(English) {
    var englishSubtopics = document.getElementById("englishSubtopicSelection");
    englishSubtopics.style.display = English.checked ? "block" : "none";
};
function showMathSubtopics(Math) {
    var mathSubtopics = document.getElementById("mathSubtopicSelection");
    mathSubtopics.style.display = Math.checked ? "block" : "none";
};
function showScienceSubtopics(Science) {
    var scienceSubtopics = document.getElementById("scienceSubtopicSelection");
    scienceSubtopics.style.display = Science.checked ? "block" : "none";
};
function showSocialStudiesSubtopics(SocialStudies) {
    var socialStudiesSubtopics = document.getElementById("socialStudiesSubtopicSelection");
    socialStudiesSubtopics.style.display = SocialStudies.checked ? "block" : "none";
};
