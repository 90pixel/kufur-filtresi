<?php

namespace DPX;

/**
 * Bu class soft ve hard dosyaları içerisinde bulunan kelimeleri filtreleme işlevi görmesi için hazırlanmıştır.
 *
 * @package \DPX\Filter
 */
class Filter
{
    /**
     * Hard kelimelerinin bulunduğu dosyanın adı
     *
     * @var string
     */
    private $hardFile = '../hard.txt';

    /**
     * Hard kelimeler için kullanılacak regex
     *
     * @var string
     */
    private $hardRegex = '/(%s)/ui';

    /**
     * Soft kelimelerinin bulunduğu dosyanın adı
     *
     * @var string
     */
    private $softFile = '../soft.txt';

    /**
     * Soft kelimeler için kullanılacak regex
     *
     * @var string
     */
    private $softRegex = '/(\b)+(%s)+(\b)/ui';

    /**
     * Filtrelenecek dosyaların tutulduğu dizi
     *
     * @var array
     */
    private $dictionary = [];

    /**
     * Filtrelenecek cümle, kelime vs.
     *
     * @var string
     */
    private $text;

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        $this->initDictionary();
    }

    /**
     * Filtreleme yapılacak dosyaların içeriğini dictionary değişkeninde toplanması işlevini yerine getirir.
     *
     * @return Filter
     */
    public function initDictionary(): Filter
    {
        $hard = $this->getHardFile();
        $soft = $this->getSoftFile();

        if (!file_exists($hard) || !file_exists($soft)) {
            return $this;
        }

        $dictionary = [
            'hard' => $this->getResourceData($hard),
            'soft' => $this->getResourceData($soft),
        ];

        $this->setDictionary($dictionary);

        return $this;
    }

    /**
     * Dosyaları okur ve içerisinde bulunan kelimeleri regex'e uygun hale getirir.
     *
     * @param string $file
     * @return string
     */
    private function getResourceData(string $file): string
    {
        return implode('|', explode("\n", file_get_contents($file)));
    }

    /**
     * Hard kelimelerinin bulunduğu dosyanın adını döndürür.
     *
     * @return string
     */
    public function getHardFile(): string
    {
        return $this->hardFile;
    }

    /**
     * Hard kelimelerin olduğu farklı bir dosyayı ayarlamaya yarar.
     *
     * @param string $hardFile
     * @return Filter
     */
    public function setHardFile(string $hardFile): Filter
    {
        $this->hardFile = $hardFile;

        return $this;
    }

    /**
     * Soft kelimelerinin bulunduğu dosyanın adını döndürür.
     *
     * @return string
     */
    public function getSoftFile(): string
    {
        return $this->softFile;
    }

    /**
     * Soft kelimelerin olduğu farklı bir dosyayı ayarlamaya yarar.
     *
     * @param string $softFile
     * @return Filter
     */
    public function setSoftFile(string $softFile): Filter
    {
        $this->softFile = $softFile;

        return $this;
    }

    /**
     * Dosyalar içerisinde ki kelimelerin toplandığı diziyi döndürür.
     *
     * @return array
     */
    public function getDictionary(): array
    {
        return $this->dictionary;
    }

    /**
     * Filtrelenecek kelimelerin değişkene set etme işlevini görür.
     *
     * @param array $dictionary
     * @return Filter
     */
    public function setDictionary(array $dictionary): Filter
    {
        $this->dictionary = $dictionary;

        return $this;
    }

    /**
     * Filtrelenecek cümle, kelime vs. neyse onu döndürür.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Filtrelenecek cümle, kelime vs. set etme işlevini görür.
     *
     * @param string $text
     * @return Filter
     */
    public function setText(string $text): Filter
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Hard filter için kullanılacak regex ifadeyi döndürür.
     *
     * @return string
     */
    public function getHardRegex(): string
    {
        return $this->hardRegex;
    }

    /**
     * Hard filter için kullanılacak regex ifadeyi set eder.
     *
     * @param string $hardRegex
     * @return Filter
     */
    public function setHardRegex(string $hardRegex): Filter
    {
        $this->hardRegex = $hardRegex;

        return $this;
    }

    /**
     * Soft filter için kullanılacak regex ifadeyi döndürür.
     *
     * @return string
     */
    public function getSoftRegex(): string
    {
        return $this->softRegex;
    }

    /**
     * Soft filter için kullanılacak regex ifadeyi set eder.
     *
     * @param string $softRegex
     * @return Filter
     */
    public function setSoftRegex(string $softRegex): Filter
    {
        $this->softRegex = $softRegex;

        return $this;
    }

    /**
     * Filtrelenecek cümle, kelime vs. içerisinde hard kelimelerden var mı diye kontrol eder.
     *
     * @return bool
     */
    public function checkHard(): bool
    {
        $dictionary = $this->getDictionary();

        $regex = sprintf($this->getHardRegex(), $dictionary["hard"]);

        return (bool) preg_match($regex, $this->getText());
    }

    /**
     * Filtrelenecek cümle, kelime vs. içerisinde soft kelimelerden var mı diye kontrol eder.
     *
     * @return bool
     */
    public function checkSoft(): bool
    {
        $dictionary = $this->getDictionary();

        $regex = sprintf($this->getSoftRegex(), $dictionary["soft"]);

        return (bool) preg_match($regex, $this->getText());
    }

    /**
     * Filtrelenecek cümle, kelime vs. değişkeni içerisinde hard kelimeleri,
     * vereceğiniz $replaceText değişkeni ile değiştirir.
     *
     * @param string $replaceText
     * @return string
     */
    public function replaceHard(string $replaceText): string
    {
        $dictionary = $this->getDictionary();

        $regex = sprintf($this->getHardRegex(), $dictionary["hard"]);

        $text = preg_replace($regex, "__DPX__", $this->getText());

        $parse = array_map(function($item) use ($replaceText) {
            if (preg_match("/__DPX__/", $item)) {
                return $replaceText;
            }

            return $item;
        }, preg_split("/\s/", $text));

        return implode(" ", $parse);
    }

    /**
     * Filtrelenecek cümle, kelime vs. değişkeni içerisinde soft kelimeleri,
     * vereceğiniz $replaceText değişkeni ile değiştirir.
     *
     * @param string $replaceText
     * @return string
     */
    public function replaceSoft(string $replaceText): string
    {
        $dictionary = $this->getDictionary();

        $regex = sprintf($this->getSoftRegex(), $dictionary["soft"]);

        return preg_replace($regex, $replaceText, $this->getText());
    }

    /**
     * Filtrelenecek cümle, kelime vs. değişkeni içerisinde önce soft sonra hard olmak üzere,
     * vereceğiniz $replacementText değeri ile filtreler.
     *
     * @param string $replaceText
     * @return string
     */
    public function replace(string $replaceText): string
    {
        $softText = $this->replaceSoft($replaceText);

        $this->setText($softText);

        $hardText = $this->replaceHard($replaceText);

        return $hardText;
    }
}