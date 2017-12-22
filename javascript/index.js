const Filter = require('./Filter');

const text = "yarak kürek işler bunlar amk tuzlayarak ansiklopedi";

const replacement = ["🤐", "😡", "😤", "😠"];
const rnd = Math.floor(Math.random() * replacement.length);

const f = new Filter();
const t = f.setHardFile("../hard.txt")
    .setSoftFile("../soft.txt")
    .initDictionary()
    .setText(text)
    .replace(replacement[rnd])
;

console.log(t);
