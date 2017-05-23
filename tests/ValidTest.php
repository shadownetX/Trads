<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ValidTest
 * @covers \Trads\ValidTraductor
 */
class ValidTest extends TestCase
{
    public function testLanguageExist()
    {
        foreach (\Trads\ValidTraductor::getLanguages() as $lang) {

            $this->assertEquals(2, strlen($lang));
            $this->assertEquals(true, ctype_alpha($lang));
            $this->assertDirectoryExists($lang);
        }
    }

    public function testRessourceExist()
    {
        foreach (\Trads\ValidTraductor::getRessources() as $ressource) {

            foreach (\Trads\ValidTraductor::getLanguages() as $lang) {

                $dir_json = $lang . '/' . $ressource . '.json';
                $json = file_get_contents($dir_json);

                $this->assertFileExists($dir_json);
                $this->assertJson($json);
                $this->assertNotEmpty($json);
            }
        }
    }

}
