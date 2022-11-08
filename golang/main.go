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
		r, err := regexp.Compile(regex)
		if err != nil {
			log.Print(fmt.Sprintf("[%s] bu kelime regex'i saglamiyor: %v", v, err))
			continue
		}
		if r.MatchString(text) {
			text = strings.Replace(text, v, redAngry.String(), 1)
		}
	}

	for _, v := range softList {
		var regex = fmt.Sprintf("(\\b)+(%s)+(\\b)", v)
		r, _ := regexp.Compile(regex)
		if r.MatchString(text) {
			text = strings.Replace(text, v, angry.String(), 1)
		}
	}

	fmt.Println(text)
}
