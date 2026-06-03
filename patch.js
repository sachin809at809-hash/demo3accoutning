const fs = require('fs');
let data = fs.readFileSync('public/js/common/dashboards.min.js', 'utf8');

data = data.replace(
    "scrollLeft.addEventListener('click', function () {", 
    "if(scrollLeft&&scrollRight){scrollLeft.addEventListener('click', function () {"
);

data = data.replace(
    "scrollRight.addEventListener('click', function () {\n        return scrollToItem('right');\n      });\n", 
    "scrollRight.addEventListener('click', function () {\n        return scrollToItem('right');\n      });}\n"
);

// Fallback replace without exact whitespace
data = data.replace(
    /scrollRight\.addEventListener\('click', function \(\) \{\s*return scrollToItem\('right'\);\s*\}\);/,
    "scrollRight.addEventListener('click', function () { return scrollToItem('right'); });}"
);


data = data.replace(
    "function updateSlider() {", 
    "function updateSlider() { if(!slider||!scrollLeft||!scrollRight)return;"
);

fs.writeFileSync('public/js/common/dashboards.min.js', data);
console.log("Patched!");
