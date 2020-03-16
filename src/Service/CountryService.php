<?php


namespace App\Service;


use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This Service handles the synchronisation operation between the remote endpoint restcountries.eu and local Country entity.
 * Class CountryService
 * @package App\Service
 */
class CountryService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var
     */
    private $countryServiceUrl;

    /**
     * CountryService constructor.
     * @param EntityManagerInterface $entityManager
     * @param $countryServiceUrl
     */
    public function __construct(EntityManagerInterface $entityManager, $countryServiceUrl)
    {
        $this->entityManager = $entityManager;
        $this->countryServiceUrl = $countryServiceUrl;
    }

    /**
     * @param $name
     * @return object|null
     */
    public funCtion exists($name){
        return $this->entityManager->getRepository(Country::class)->findOneBy(['name'=>$name]);
    }

    public function getAllCountriesData(){

        //get data from api.
        $json = file_get_contents($this->countryServiceUrl);
        $data = json_decode($json);

        //parse data from api.
        if ($data && is_array($data)) {
            return $data;
        }
        return null;
    }

    public function syncCountries(){
        $countriesArr = $this->getAllCountriesData();
        foreach ($countriesArr as $item) {
            $country = $this->exists($item->name);
            if (!$country){
                $country = new Country();
            }
            $this->save($country, $item);
        }
    }

    private function save(Country $country, object $data){
        $country->setCapital($data->capital);
        $country->setRegion($data->region);
        $country->setCode($data->alpha2Code);
        $country->setFlag($data->flag);
        $country->setName($data->name);
        $this->entityManager->persist($country);
        $this->entityManager->flush();
        return $country;
    }
}