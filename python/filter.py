import re
import random


class Filter:
    hardFile = "../hard.txt"
    hardRegex = ""
    softFile = "../soft.txt"
    softRegex = ""
    dictionary = {}
    text = ""

    def __init__(self):
        self.init_dictionary()
        self.init_regex()

    def init_dictionary(self):
        with open(self.hardFile, 'r') as lines:
            self.dictionary['hard'] = "|".join([line.rstrip('\n') for line in lines])

        with open(self.softFile, 'r') as lines:
            self.dictionary['soft'] = "|".join([line.rstrip('\n') for line in lines])

        return self

    def init_regex(self):
        self.hardRegex = r"(" + self.dictionary['hard'] + ")"
        self.softRegex = r"(\b)+({})+(\b)".format(self.dictionary['soft'])

    def set_hard_file(self, file):
        self.hardFile = file
        return self

    def set_hard_regex(self, regex):
        self.hardRegex = regex
        return self

    def set_soft_file(self, file):
        self.softFile = file
        return self

    def set_soft_regex(self, regex):
        self.softRegex = regex
        return self

    def set_dictionary(self, dictionary):
        self.dictionary = dictionary
        return self

    def set_text(self, text):
        self.text = text
        return self

    def check_hard(self):
        matches = re.search(self.hardRegex, self.text, re.IGNORECASE | re.UNICODE)

        if matches:
            return True

        return False

    def check_soft(self):
        matches = re.search(self.softRegex, self.text, re.IGNORECASE | re.UNICODE)

        if matches:
            return True

        return False

    def replace_hard(self, replace_text):
        return re.sub(self.hardRegex, replace_text, self.text)

    def replace_soft(self, replace_text):
        return re.sub(self.softRegex, replace_text, self.text)

    def replace(self, replace_text):
        soft_text = self.replace_soft(replace_text)

        self.set_text(soft_text)

        return self.replace_hard(replace_text)


emojis = ["🤐", "😡", "😤", "😠"]

app = Filter()
text = app\
    .set_hard_file("../hard.txt")\
    .set_soft_file("../soft.txt")\
    .init_dictionary()\
    .set_text("yarak kürek işler bunlar amk tuzlayarak ansiklopedi") \
    .replace(random.choice(emojis))

print(text)