const fs = require('fs');

class Filter {
    constructor() {
        this.hardFile = "../hard.txt";
        this.hardRegex = "";
        this.softFile = "../soft.txt";
        this.softRegex = "";
        this.dictionary = {};
        this.text = "";

        this.initDictionary();
        this.initRegex();
    }

    initDictionary() {
        const getResourceData = function (file) {
            return fs.readFileSync(file, 'utf8').split("\n").join("|");
        };

        this.dictionary = {
            'hard': getResourceData(this.hardFile),
            'soft': getResourceData(this.softFile),
        };

        return this;
    }

    initRegex() {
        this.setHardRegex(new RegExp("(" + this.dictionary.hard + ")", "ui"));
        this.setSoftRegex(new RegExp("(\\b)+(" + this.dictionary.soft + ")+(\\b)", "gui"));

        return this;
    }

    setHardFile (file) {
        this.hardFile = file;

        return this;
    }

    setSoftFile (file) {
        this.softFile = file;

        return this;
    }

    setDictionary (dictionary) {
        this.dictionary = dictionary;

        return this;
    }

    setText(text) {
        this.text = text;

        return this;
    }

    setHardRegex(regex) {
        this.hardRegex = regex;

        return this;
    }

    setSoftRegex(regex) {
        this.softRegex = regex;

        return this;
    }

    checkHard() {
        return this.text.match(this.hardRegex);
    }

    checkSoft() {
        return this.text.match(this.softRegex);
    }

    replaceHard (replaceText) {
        return this.text.replace(this.hardRegex, replaceText);
    }

    replaceSoft (replaceText) {
        return this.text.replace(this.softRegex, replaceText);
    }

    replace (replaceText) {
        const softText = this.replaceSoft(replaceText);
        
        return this.setText(softText).replaceHard(replaceText);
    }
}


module.exports = Filter;