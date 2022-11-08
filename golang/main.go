package main

import (
	"bufio"
	"fmt"
	"log"
	"os"
	"regexp"
	"strings"

	"github.com/enescakir/emoji"
)

var (
	angry     = emoji.AngryFace              // "😠"
	redAngry  = emoji.FaceWithSymbolsOnMouth // "😡"
	zip       = emoji.ZipperMouthFace        // "🤐"
	withSteam = emoji.FaceWithSteamFromNose  // "😤"
)

var text = "yarak kürek işler bunlar amk tuzlayarak ansiklopediyi alma taşağa"

func main() {
	var softList = readFile("../soft.txt")
	var hardList = readFile("../hard.txt")

	for _, v := range hardList {
		var regex = fmt.Sprintf("(%s)", v)
		check(regex, v, redAngry.String())
	}

	for _, v := range softList {
		var regex = fmt.Sprintf("(\\b)+(%s)+(\\b)", v)
		check(regex, v, angry.String())
	}

	fmt.Println(text)
}

func readFile(filepath string) []string {
	file, err := os.Open(filepath)
	if err != nil {
		log.Fatal(err)
	}
	defer file.Close()

	var scanner = bufio.NewScanner(file)

	var list = make([]string, 0)
	for scanner.Scan() {
		list = append(list, scanner.Text())
	}

	return list
}

func check(regex string, v string, emoji string) {
	r, err := regexp.Compile(regex)
	if err != nil {
		log.Print(fmt.Sprintf("[%s] bu kelime regex'i saglamiyor: %v", v, err))
		return
	}
	if r.MatchString(text) {
		text = strings.Replace(text, v, emoji, 1)
	}
}
