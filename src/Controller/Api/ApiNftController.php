<?php

namespace App\Controller\Api;

use App\Entity\Nft;
use App\Repository\AudioRepository;
use App\Repository\ImageRepository;
use App\Repository\NftRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route("api/")]
class ApiNftController extends AbstractController
{

    //filtering the NFTs according to the name of its subCategories | image name and order by created date
    #[Route("nfts", methods: ['GET'])]
    public function bySubCategoryName(NftRepository $nftRepository, Request $request): Response
    {


        $subCategoryName = $request->query->get("s") ? str_replace("-", " ", $request->query->get("s")) : "";
        $searchByImageName = $request->query->get("n") ? str_replace("-", " ", $request->query->get("n")) : null;
        $maxNumberOfNfts = $request->query->get('m') ?: null;
        $orderBy = $request->query->get("o") ?: null;


        if ($subCategoryName === "all" || $subCategoryName === "") { // selecting all nft if no para set or set to all for subcategories

            if ($searchByImageName != null || $orderBy != null) {
                $qb = $nftRepository->createQueryBuilder("n");
                if ($searchByImageName != null) {

                    $qb = $qb->join("n.image", "i")
                        ->where("i.name LIKE :imageName")
                        ->setParameter("imageName", "%" . $searchByImageName . "%");
                }


                if ($orderBy === "asc" || $orderBy === "desc") { // if an order by is specified

                    $qb->orderBy("n.createdAt", $orderBy);

                }

                $nfts = $qb->setMaxResults($str)
                    ->getQuery()
                    ->getResult();

            } else { // if no order by is specified or name
                $nfts = $nftRepository->createQueryBuilder("n")
                    ->setMaxResults($maxNumberOfNfts)
                    ->getQuery()
                    ->getResult();
            }
        } else { //selecting nft of certian subCAtegory
            $qb = $nftRepository->createQueryBuilder("n")
                ->join("n.subCategory", "s")
                ->where("s.name = :subName")
                ->setParameter("subName", $subCategoryName)
                ->setMaxResults($maxNumberOfNfts);
            if ($searchByImageName) {
                $qb = $qb->join("n.image", "i")
                    ->where("i.name = imageName")
                    ->setParameter("imageName", $searchByImageName);
            }

            if ($orderBy == "asc" || $orderBy == "desc") { // if an order by is specified
                $qb = $qb->orderBy("n.createdAt", $orderBy)
                    ->setMaxResults($maxNumberOfNfts);
            }

            $nfts = $qb->getQuery()->getResult();

        }

        if (count($nfts) > 0) { // avoiding passing wrong parameters in url
            return $this->json($nfts, context: ["groups" => ["nft"]]);

        }
        return $this->json("No results found for this SubCategory");


    }


    // Selecting a nft by its id
    #[Route("nft")]
    public function byId(Request $request, NftRepository $nftRepository): Response
    {
        $nftId = $request->query->get("i");
        $nft = $nftRepository->find($nftId) ?: "";

        if ($nft) {
            return $this->json($nft, context: ["groups" => ["nft"]]);

        } else {
            return $this->json("No results found.");
        }

    }

    // Selecting a specific NFT according to name of its media using slug
    #[Route("nft/{slug}", methods: ["GET"])]
    public function byMediaName(NftRepository   $nftRepository, SluggerInterface $slugger, string $slug,
                                ImageRepository $imageRepository, VideoRepository $videoRepository, AudioRepository $audioRepository)
    {
        // we use slug for the name of the media ( image, audio , or video)
        $mediaName = str_replace("-", " ", $slug);

        // we check for what type of media does the nft related to
        $image = $imageRepository->findBy(["name" => $mediaName]); // the method findBy returns an array of class
        $video = $videoRepository->findBy(["name" => $mediaName]);
        $audio = $audioRepository->findBy(["name" => $mediaName]);

        if ($image) { //if the media is an image
            $imageId = $image[0]->getId();
            $nft = $nftRepository->findBy(["image" => $imageId]);

        } else if ($video) { // if the media is a video
            $videoId = $video[0]->getId();
            $nft = $nftRepository->findBy(["image" => $videoId]);

        } else if ($audio) { //if the media is an audio
            $audioId = $audio[0]->getId();
            $nft = $nftRepository->findBy(["image" => $audioId]);

        } else { // if no results found for the slug
            return $this->json("No results found");
        }

        return $this->json($nft, context: ["groups" => ["nftBySlug"]]);


    }


    // Selecting a specific Nft according to its ID
    #[Route("nft/show/{id}", methods: ['GET'])]
    public function showByID(Request $request, NftRepository $nftRepository, string $id)
    {

        if ($id != "") {
            $nft = $nftRepository->find($id);
            return $this->json($nft, context: ["groups" => ["nft"]]);
        }
        return $this->json("No result found");


    }

    #[Route("nftsquantity")]
    public function getTotalQuantity(NftRepository $nftRepository): Response
    {

        $nfts = $nftRepository->findAll();
        $quantity = count($nfts);

        return new Response($quantity);
    }
}