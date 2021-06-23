<?php

if (!class_exists('KcSeoSchemaModel')):
    class KcSeoSchemaModel
    {

        function __construct() {

        }

        function schemaOutput($schemaID, $metaData) {
            $html = null;

            if ($schemaID) {
                global $KcSeoWPSchema;
                switch ($schemaID) {
                    case "article":
                        $article = array(
                            "@context" => "http://schema.org",
                            "@type"    => "Article"
                        );
                        if (!empty($metaData['headline'])) {
                            $article["headline"] = $KcSeoWPSchema->sanitizeOutPut($metaData['headline']);
                        }
                        if (!empty($metaData['mainEntityOfPage'])) {
                            $article["mainEntityOfPage"] = array(
                                "@type" => "WebPage",
                                "@id"   => $KcSeoWPSchema->sanitizeOutPut($metaData["mainEntityOfPage"])
                            );
                        }
                        if (!empty($metaData['author'])) {
                            $article["author"] = array(
                                "@type" => "Person",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['author'])
                            );
                        }
                        if (!empty($metaData['publisher'])) {
                            if (!empty($metaData['publisherImage'])) {
                                $img = $KcSeoWPSchema->imageInfo(absint($metaData['publisherImage']));
                                $plA = array(
                                    "@type"  => "ImageObject",
                                    "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                    "height" => $img['height'],
                                    "width"  => $img['width']
                                );
                            } else {
                                $plA = array();
                            }
                            $article["publisher"] = array(
                                "@type" => "Organization",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['publisher']),
                                "logo"  => $plA
                            );
                        }
                        if (!empty($metaData['alternativeHeadline'])) {
                            $article["alternativeHeadline"] = $KcSeoWPSchema->sanitizeOutPut($metaData['alternativeHeadline']);
                        }
                        if (!empty($metaData['image'])) {
                            $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                            $article["image"] = array(
                                "@type"  => "ImageObject",
                                "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                "height" => $img['height'],
                                "width"  => $img['width']
                            );
                        }
                        if (!empty($metaData['datePublished'])) {
                            $article["datePublished"] = $KcSeoWPSchema->sanitizeOutPut($metaData['datePublished']);
                        }
                        if (!empty($metaData['dateModified'])) {
                            $article["dateModified"] = $KcSeoWPSchema->sanitizeOutPut($metaData['dateModified']);
                        }
                        if (!empty($metaData['description'])) {
                            $article["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if (!empty($metaData['articleBody'])) {
                            $article["articleBody"] = $KcSeoWPSchema->sanitizeOutPut($metaData['articleBody'],
                                'textarea');
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_article', $article, $metaData));
                        break;

                    case "news_article":
                        $newsArticle = array(
                            "@context" => "http://schema.org",
                            "@type"    => "NewsArticle"
                        );
                        if (!empty($metaData['headline'])) {
                            $newsArticle["headline"] = $KcSeoWPSchema->sanitizeOutPut($metaData['headline']);
                        }
                        if (!empty($metaData['mainEntityOfPage'])) {
                            $newsArticle["mainEntityOfPage"] = array(
                                "@type" => "WebPage",
                                "@id"   => $KcSeoWPSchema->sanitizeOutPut($metaData["mainEntityOfPage"])
                            );
                        }
                        if (!empty($metaData['author'])) {
                            $newsArticle["author"] = array(
                                "@type" => "Person",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['author'])
                            );
                        }
                        if (!empty($metaData['image'])) {
                            $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                            $newsArticle["image"] = array(
                                "@type"  => "ImageObject",
                                "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                "height" => $img['height'],
                                "width"  => $img['width']
                            );
                        }
                        if (!empty($metaData['datePublished'])) {
                            $newsArticle["datePublished"] = $KcSeoWPSchema->sanitizeOutPut($metaData['datePublished']);
                        }
                        if (!empty($metaData['dateModified'])) {
                            $newsArticle["dateModified"] = $KcSeoWPSchema->sanitizeOutPut($metaData['dateModified']);
                        }
                        if (!empty($metaData['publisher'])) {
                            if (!empty($metaData['publisherImage'])) {
                                $img = $KcSeoWPSchema->imageInfo(absint($metaData['publisherImage']));
                                $plA = array(
                                    "@type"  => "ImageObject",
                                    "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                    "height" => $img['height'],
                                    "width"  => $img['width']
                                );
                            } else {
                                $plA = array();
                            }
                            $newsArticle["publisher"] = array(
                                "@type" => "Organization",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['publisher']),
                                "logo"  => $plA
                            );
                        }
                        if (!empty($metaData['description'])) {
                            $newsArticle["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if (!empty($metaData['articleBody'])) {
                            $newsArticle["articleBody"] = $KcSeoWPSchema->sanitizeOutPut($metaData['articleBody'],
                                'textarea');
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_news_article', $newsArticle, $metaData));
                        break;

                    case "blog_posting":
                        $blogPosting = array(
                            "@context" => "http://schema.org",
                            "@type"    => "BlogPosting"
                        );
                        if (!empty($metaData['headline'])) {
                            $blogPosting["headline"] = $KcSeoWPSchema->sanitizeOutPut($metaData['headline']);
                        }
                        if (!empty($metaData['mainEntityOfPage'])) {
                            $blogPosting["mainEntityOfPage"] = array(
                                "@type" => "WebPage",
                                "@id"   => $KcSeoWPSchema->sanitizeOutPut($metaData["mainEntityOfPage"])
                            );
                        }
                        if (!empty($metaData['author'])) {
                            $blogPosting["author"] = array(
                                "@type" => "Person",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['author'])
                            );
                        }
                        if (!empty($metaData['image'])) {
                            $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                            $blogPosting["image"] = array(
                                "@type"  => "ImageObject",
                                "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                "height" => $img['height'],
                                "width"  => $img['width']
                            );
                        }
                        if (!empty($metaData['datePublished'])) {
                            $blogPosting["datePublished"] = $KcSeoWPSchema->sanitizeOutPut($metaData['datePublished']);
                        }
                        if (!empty($metaData['dateModified'])) {
                            $blogPosting["dateModified"] = $KcSeoWPSchema->sanitizeOutPut($metaData['dateModified']);
                        }
                        if (!empty($metaData['publisher'])) {
                            if (!empty($metaData['publisherImage'])) {
                                $img = $KcSeoWPSchema->imageInfo(absint($metaData['publisherImage']));
                                $plA = array(
                                    "@type"  => "ImageObject",
                                    "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                    "height" => $img['height'],
                                    "width"  => $img['width']
                                );
                            } else {
                                $plA = array();
                            }
                            $blogPosting["publisher"] = array(
                                "@type" => "Organization",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['publisher']),
                                "logo"  => $plA
                            );
                        }
                        if (!empty($metaData['description'])) {
                            $blogPosting["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if (!empty($metaData['articleBody'])) {
                            $blogPosting["articleBody"] = $KcSeoWPSchema->sanitizeOutPut($metaData['articleBody'],
                                'textarea');
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_blog_posting', $blogPosting, $metaData));
                        break;

                    case 'event':
                        $event = array(
                            "@context" => "http://schema.org",
                            "@type"    => "Event"
                        );
                        if (!empty($metaData['name'])) {
                            $event["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['startDate'])) {
                            $event["startDate"] = $KcSeoWPSchema->sanitizeOutPut($metaData['startDate']);
                        }
                        if (!empty($metaData['endDate'])) {
                            $event["endDate"] = $KcSeoWPSchema->sanitizeOutPut($metaData['endDate']);
                        }
                        if (!empty($metaData['description'])) {
                            $event["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if (!empty($metaData['performerName'])) {
                            $event["performer"] = array(
                                "@type" => "Person",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['performerName'])
                            );
                        }
                        if (!empty($metaData['image'])) {
                            $event["image"] = $KcSeoWPSchema->sanitizeOutPut($metaData['image'], 'url');
                        }
                        if (!empty($metaData['locationName'])) {
                            $event["location"] = array(
                                "@type"   => "Place",
                                "name"    => $KcSeoWPSchema->sanitizeOutPut($metaData['locationName']),
                                "address" => $KcSeoWPSchema->sanitizeOutPut($metaData['locationAddress'])
                            );
                        }
                        if (!empty($metaData['price'])) {
                            $event["offers"] = array(
                                "@type" => "Offer",
                                "price" => $KcSeoWPSchema->sanitizeOutPut($metaData['price'])
                            );
                            if (!empty($metaData['priceCurrency'])) {
                                $event["offers"]['priceCurrency'] = $KcSeoWPSchema->sanitizeOutPut($metaData['priceCurrency']);
                            }
                            if (!empty($metaData['url'])) {
                                $event["offers"]['url'] = $KcSeoWPSchema->sanitizeOutPut($metaData['url'], 'url');
                            }
                            if (!empty($metaData['availability'])) {
                                $event["offers"]['availability'] = $KcSeoWPSchema->sanitizeOutPut($metaData['availability']);
                            }
                            if (!empty($metaData['validFrom'])) {
                                $event["offers"]['validFrom'] = $KcSeoWPSchema->sanitizeOutPut($metaData['validFrom']);
                            }
                        }
                        if (!empty($metaData['url'])) {
                            $event["url"] = $KcSeoWPSchema->sanitizeOutPut($metaData['url'], 'url');
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_event', $event, $metaData));
                        if (isset($metaData['review_active'])) {
                            $event_review = array(
                                "@context" => "http://schema.org",
                                "@type"    => "Review"
                            );

                            if (isset($metaData['review_datePublished']) && !empty($metaData['review_datePublished'])) {
                                $event_review["datePublished"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_datePublished']);
                            }
                            if (isset($metaData['review_body']) && !empty($metaData['review_body'])) {
                                $event_review["reviewBody"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_body'], 'textarea');
                            }
                            unset($event['@context']);
                            $event_review["itemReviewed"] = $event;
                            if (!empty($metaData['review_author'])) {
                                $event_review["author"] = array(
                                    "@type" => "Person",
                                    "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['review_author'])
                                );

                                if (isset($metaData['review_author_sameAs']) && !empty($metaData['review_author_sameAs'])) {
                                    $sameAs = KcSeoHelper::get_same_as($KcSeoWPSchema->sanitizeOutPut($metaData['review_author_sameAs'], "textarea"));
                                    if (!empty($sameAs)) {
                                        $event_review["author"]["sameAs"] = $sameAs;
                                    }
                                }
                            }
                            if (isset($metaData['review_ratingValue'])) {
                                $event_review["reviewRating"] = array(
                                    "@type"       => "Rating",
                                    "ratingValue" => $KcSeoWPSchema->sanitizeOutPut($metaData['review_ratingValue'], 'number')
                                );
                                if (isset($metaData['review_bestRating'])) {
                                    $event_review["reviewRating"]["bestRating"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_bestRating'], 'number');
                                }
                                if (isset($metaData['review_worstRating'])) {
                                    $event_review["reviewRating"]["worstRating"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_worstRating'], 'number');
                                }
                            }


                            $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_event_review', $event_review, $metaData));
                        }
                        break;

                    case 'product':
                        $product = array(
                            "@context" => "http://schema.org",
                            "@type"    => "Product"
                        );
                        if (!empty($metaData['name'])) {
                            $product["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['image'])) {
                            $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                            $product["image"] = $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url');
                        }
                        if (!empty($metaData['description'])) {
                            $product["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description']);
                        }
                        /* product identifier */
                        if (!empty($metaData['sku'])) {
                            $product["sku"] = $KcSeoWPSchema->sanitizeOutPut($metaData['sku']);
                        }
                        if (!empty($metaData['brand'])) {
                            $product["brand"] = $KcSeoWPSchema->sanitizeOutPut($metaData['brand']);
                        }
                        if (!empty($metaData['identifier_type']) && !empty($metaData['identifier'])) {
                            $product[$metaData['identifier_type']] = $KcSeoWPSchema->sanitizeOutPut($metaData['identifier']);
                        }
                        if (!empty($metaData['ratingValue'])) {
                            $product["aggregateRating"] = array(
                                "@type"       => "AggregateRating",
                                "ratingValue" => !empty($metaData['ratingValue']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['ratingValue']) : null,
                                "reviewCount" => !empty($metaData['reviewCount']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['reviewCount']) : null
                            );
                        }
                        if (!empty($metaData['reviewRatingValue']) || !empty($metaData['reviewBestRating']) || !empty($metaData['reviewWorstRating'])) {
                            $product["review"] = array(
                                "@type"        => "Review",
                                "reviewRating" => array(
                                    "@type"       => "Rating",
                                    "ratingValue" => !empty($metaData['reviewRatingValue']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['reviewRatingValue']) : null,
                                    "bestRating"  => !empty($metaData['reviewBestRating']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['reviewBestRating']) : null,
                                    "worstRating" => !empty($metaData['reviewWorstRating']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['reviewWorstRating']) : null
                                ),
                                "author"       => array(
                                    "@type" => "Person",
                                    "name"  => !empty($metaData['reviewAuthor']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['reviewAuthor']) : null
                                )
                            );
                        }
                        if (!empty($metaData['price'])) {
                            $product["offers"] = array(
                                "@type"           => "Offer",
                                "price"           => $KcSeoWPSchema->sanitizeOutPut($metaData['price']),
                                "priceValidUntil" => $KcSeoWPSchema->sanitizeOutPut($metaData['priceValidUntil']),
                                "priceCurrency"   => !empty($metaData['priceCurrency']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['priceCurrency']) : null,
                                "itemCondition"   => !empty($metaData['itemCondition']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['itemCondition']) : null,
                                "availability"    => !empty($metaData['availability']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['availability']) : null,
                                "url"             => !empty($metaData['url']) ? $KcSeoWPSchema->sanitizeOutPut($metaData['url']) : null
                            );
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_product', $product, $metaData));
                        break;

                    case 'video':
                        $video = array(
                            "@context" => "http://schema.org",
                            "@type"    => "VideoObject"
                        );
                        if (!empty($metaData['name'])) {
                            $video["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['description'])) {
                            $video["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'], 'textarea');
                        }
                        if (!empty($metaData['thumbnailUrl'])) {
                            $video["thumbnailUrl"] = $KcSeoWPSchema->sanitizeOutPut($metaData['thumbnailUrl'], 'url');
                        }
                        if (!empty($metaData['uploadDate'])) {
                            $video["uploadDate"] = $KcSeoWPSchema->sanitizeOutPut($metaData['uploadDate']);
                        }
                        if (!empty($metaData['duration'])) {
                            $video["duration"] = $KcSeoWPSchema->sanitizeOutPut($metaData['duration']);
                        }
                        if (!empty($metaData['contentUrl'])) {
                            $video["contentUrl"] = $KcSeoWPSchema->sanitizeOutPut($metaData['contentUrl'], 'url');
                        }
                        if (!empty($metaData['embedUrl'])) {
                            $video["embedUrl"] = $KcSeoWPSchema->sanitizeOutPut($metaData['embedUrl'], 'url');
                        }
                        if (!empty($metaData['interactionCount'])) {
                            $video["interactionCount"] = $KcSeoWPSchema->sanitizeOutPut($metaData['interactionCount']);
                        }
                        if (!empty($metaData['expires'])) {
                            $video["expires"] = $KcSeoWPSchema->sanitizeOutPut($metaData['expires']);
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_video', $video, $metaData));
                        break;

                    case 'service':
                        $service = array(
                            "@context" => "http://schema.org",
                            "@type"    => "Service"
                        );
                        if (!empty($metaData['name'])) {
                            $service["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['serviceType'])) {
                            $service["serviceType"] = $KcSeoWPSchema->sanitizeOutPut($metaData['serviceType']);
                        }
                        if (!empty($metaData['award'])) {
                            $service["award"] = $KcSeoWPSchema->sanitizeOutPut($metaData['award']);
                        }
                        if (!empty($metaData['category'])) {
                            $service["category"] = $KcSeoWPSchema->sanitizeOutPut($metaData['category']);
                        }
                        if (!empty($metaData['providerMobility'])) {
                            $service["providerMobility"] = $KcSeoWPSchema->sanitizeOutPut($metaData['providerMobility']);
                        }
                        if (!empty($metaData['additionalType'])) {
                            $service["additionalType"] = $KcSeoWPSchema->sanitizeOutPut($metaData['additionalType']);
                        }
                        if (!empty($metaData['alternateName'])) {
                            $service["alternateName"] = $KcSeoWPSchema->sanitizeOutPut($metaData['alternateName']);
                        }
                        if (!empty($metaData['image'])) {
                            $service["image"] = $KcSeoWPSchema->sanitizeOutPut($metaData['image']);
                        }
                        if (!empty($metaData['mainEntityOfPage'])) {
                            $service["mainEntityOfPage"] = $KcSeoWPSchema->sanitizeOutPut($metaData['mainEntityOfPage']);
                        }
                        if (!empty($metaData['sameAs'])) {
                            $service["sameAs"] = $KcSeoWPSchema->sanitizeOutPut($metaData['sameAs']);
                        }
                        if (!empty($metaData['url'])) {
                            $service["url"] = $KcSeoWPSchema->sanitizeOutPut($metaData['url'], 'url');
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_service', $service, $metaData));
                        break;

                    case 'review':
                        $review = array(
                            "@context" => "http://schema.org",
                            "@type"    => "Review"
                        );
                        if (!empty($metaData['itemName'])) {
                            $review["itemReviewed"] = array(
                                "@type" => "product",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['itemName'])
                            );
                        }
                        if (!empty($metaData['ratingValue'])) {
                            $review["reviewRating"] = array(
                                "@type"       => "Rating",
                                "ratingValue" => $KcSeoWPSchema->sanitizeOutPut($metaData['ratingValue']),
                                "bestRating"  => $KcSeoWPSchema->sanitizeOutPut($metaData['bestRating']),
                                "worstRating" => $KcSeoWPSchema->sanitizeOutPut($metaData['worstRating'])
                            );
                        }
                        if (!empty($metaData['name'])) {
                            $review["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['author'])) {
                            $review["author"] = array(
                                "@type" => "Person",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['author'])
                            );
                        }
                        if (!empty($metaData['reviewBody'])) {
                            $review["reviewBody"] = $KcSeoWPSchema->sanitizeOutPut($metaData['reviewBody']);
                        }
                        if (!empty($metaData['datePublished'])) {
                            $review["datePublished"] = $KcSeoWPSchema->sanitizeOutPut($metaData['datePublished']);
                        }
                        if (!empty($metaData['publisher'])) {
                            $review["publisher"] = array(
                                "@type" => "Organization",
                                "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['publisher'])
                            );
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_review', $review, $metaData));
                        break;
                    case 'aggregate_rating':
                        $aRating = array(
                            "@context" => "http://schema.org",
                            "@type"    => !empty($metaData['schema_type']) ? $metaData['schema_type'] : "LocalBusiness"
                        );
                        if (!empty($metaData['name'])) {
                            $aRating["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['description'])) {
                            $aRating["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if ($aRating["@type"] != "Organization") {
                            if (!empty($metaData['image'])) {
                                $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                                $aRating["image"] = array(
                                    "@type"  => "ImageObject",
                                    "url"    => $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url'),
                                    "height" => $img['height'],
                                    "width"  => $img['width']
                                );
                            }
                            if (!empty($metaData['priceRange'])) {
                                $aRating["priceRange"] = $KcSeoWPSchema->sanitizeOutPut($metaData['priceRange']);
                            }
                            if (!empty($metaData['telephone'])) {
                                $aRating["telephone"] = $KcSeoWPSchema->sanitizeOutPut($metaData['telephone']);
                            }

                            if (!empty($metaData['address'])) {
                                $aRating["address"] = $KcSeoWPSchema->sanitizeOutPut($metaData['address']);
                            }
                        }

                        if (!empty($metaData['ratingValue'])) {
                            $rValue = array();
                            $rValue["@type"] = "AggregateRating";
                            $rValue["ratingValue"] = $KcSeoWPSchema->sanitizeOutPut($metaData['ratingValue']);
                            if (!empty($metaData['bestRating'])) {
                                $rValue["bestRating"] = $KcSeoWPSchema->sanitizeOutPut($metaData['bestRating']);
                            }
                            if (!empty($metaData['worstRating'])) {
                                $rValue["worstRating"] = $KcSeoWPSchema->sanitizeOutPut($metaData['worstRating']);
                            }
                            if (!empty($metaData['ratingCount'])) {
                                $rValue["ratingCount"] = $KcSeoWPSchema->sanitizeOutPut($metaData['ratingCount']);
                            }

                            $aRating["aggregateRating"] = $rValue;
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_aggregate_rating', $aRating, $metaData));
                        break;

                    case 'restaurant':
                        $restaurant = array(
                            "@context" => "http://schema.org",
                            "@type"    => "Restaurant"
                        );
                        if (!empty($metaData['name'])) {
                            $restaurant["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['description'])) {
                            $restaurant["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if (!empty($metaData['openingHours'])) {
                            $restaurant["openingHours"] = $KcSeoWPSchema->sanitizeOutPut($metaData['openingHours'],
                                'textarea');
                        }
                        if (!empty($metaData['telephone'])) {
                            $restaurant["telephone"] = $KcSeoWPSchema->sanitizeOutPut($metaData['telephone']);
                        }
                        if (!empty($metaData['menu'])) {
                            $restaurant["menu"] = $KcSeoWPSchema->sanitizeOutPut($metaData['menu'], 'url');
                        }
                        if (!empty($metaData['image'])) {
                            $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                            $restaurant["image"] = $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url');
                        }
                        if (!empty($metaData['address'])) {
                            $restaurant["address"] = $KcSeoWPSchema->sanitizeOutPut($metaData['address'], 'textarea');
                        }
                        if (!empty($metaData['priceRange'])) {
                            $restaurant["priceRange"] = $KcSeoWPSchema->sanitizeOutPut($metaData['priceRange']);
                        }
                        if (!empty($metaData['servesCuisine'])) {
                            $restaurant["servesCuisine"] = $KcSeoWPSchema->sanitizeOutPut($metaData['servesCuisine']);
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_restaurant', $restaurant, $metaData));
                        break;

                    case 'localBusiness':
                        $local_business = array(
                            "@context" => "http://schema.org",
                            "@type"    => "LocalBusiness"
                        );
                        if (!empty($metaData['name'])) {
                            $local_business["name"] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['description'])) {
                            $local_business["description"] = $KcSeoWPSchema->sanitizeOutPut($metaData['description'],
                                'textarea');
                        }
                        if (!empty($metaData['image'])) {
                            $img = $KcSeoWPSchema->imageInfo(absint($metaData['image']));
                            $local_business["image"] = $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url');
                        }
                        if (!empty($metaData['priceRange'])) {
                            $local_business["priceRange"] = $KcSeoWPSchema->sanitizeOutPut($metaData['priceRange']);
                        }
                        if (!empty($metaData['addressLocality']) || !empty($metaData['addressRegion'])
                            || !empty($metaData['postalCode']) || !empty($metaData['streetAddress'])) {
                            $local_business["address"] = array(
                                "@type"           => "PostalAddress",
                                "addressLocality" => $KcSeoWPSchema->sanitizeOutPut($metaData['addressLocality']),
                                "addressRegion"   => $KcSeoWPSchema->sanitizeOutPut($metaData['addressRegion']),
                                "postalCode"      => $KcSeoWPSchema->sanitizeOutPut($metaData['postalCode']),
                                "streetAddress"   => $KcSeoWPSchema->sanitizeOutPut($metaData['streetAddress'])
                            );
                        }

                        if (!empty($metaData['telephone'])) {
                            $local_business["telephone"] = $KcSeoWPSchema->sanitizeOutPut($metaData['telephone']);
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_local_business', $local_business, $metaData));
                        if (isset($metaData['review_active'])) {
                            $local_business_review = array(
                                "@context" => "http://schema.org",
                                "@type"    => "Review",
                            );
                            if (isset($metaData['review_datePublished']) && !empty($metaData['review_datePublished'])) {
                                $local_business_review["datePublished"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_datePublished']);
                            }
                            if (isset($metaData['review_body']) && !empty($metaData['review_body'])) {
                                $local_business_review["reviewBody"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_body'], 'textarea');
                            }

                            unset($local_business['@context']);
                            if (isset($local_business["description"])) {
                                $local_business_review["description"] = KcSeoHelper::filter_content($local_business["description"], 200);
                                unset($local_business["description"]);
                            }
                            if (isset($metaData['review_sameAs']) && !empty($metaData['review_sameAs'])) {
                                $sameAs = KcSeoHelper::get_same_as($KcSeoWPSchema->sanitizeOutPut($metaData['review_sameAs'], "textarea"));
                                if (!empty($sameAs)) {
                                    $local_business["sameAs"] = $sameAs;
                                }
                            }

                            $local_business_review["itemReviewed"] = $local_business;
                            if (!empty($metaData['review_author'])) {
                                $local_business_review["author"] = array(
                                    "@type" => "Person",
                                    "name"  => $KcSeoWPSchema->sanitizeOutPut($metaData['review_author'])
                                );

                                if (isset($metaData['review_author_sameAs']) && !empty($metaData['review_author_sameAs'])) {
                                    $sameAs = KcSeoHelper::get_same_as($KcSeoWPSchema->sanitizeOutPut($metaData['review_author_sameAs'], "textarea"));
                                    if (!empty($sameAs)) {
                                        $local_business_review["author"]["sameAs"] = $sameAs;
                                    }
                                }
                            }
                            if (isset($metaData['review_ratingValue'])) {
                                $local_business_review["reviewRating"] = array(
                                    "@type"       => "Rating",
                                    "ratingValue" => $KcSeoWPSchema->sanitizeOutPut($metaData['review_ratingValue'], 'number')
                                );
                                if (isset($metaData['review_bestRating'])) {
                                    $local_business_review["reviewRating"]["bestRating"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_bestRating'], 'number');
                                }
                                if (isset($metaData['review_worstRating'])) {
                                    $local_business_review["reviewRating"]["worstRating"] = $KcSeoWPSchema->sanitizeOutPut($metaData['review_worstRating'], 'number');
                                }
                            }
                            $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_local_business_review', $local_business_review, $metaData));
                        }
                        break;
                    case 'specialAnnouncement':
                        $announcement = array(
                            "@context" => "http://schema.org",
                            "@type"    => "SpecialAnnouncement",
                            "category" => "https://www.wikidata.org/wiki/Q81068910"
                        );
                        if (!empty($metaData['name'])) {
                            $announcement['name'] = $KcSeoWPSchema->sanitizeOutPut($metaData['name']);
                        }
                        if (!empty($metaData['datePublished'])) {
                            $announcement['datePosted'] = $KcSeoWPSchema->sanitizeOutPut($metaData['datePublished']);
                        }
                        if (!empty($metaData['expires'])) {
                            $announcement['expires'] = $KcSeoWPSchema->sanitizeOutPut($metaData['expires']);
                        }
                        if (!empty($metaData['text'])) {
                            $announcement['text'] = $KcSeoWPSchema->sanitizeOutPut($metaData['text'], 'textarea');
                        }
                        if (!empty($metaData['url'])) {
                            $announcement['url'] = $KcSeoWPSchema->sanitizeOutPut($metaData['url'], 'url');
                        }
                        if (isset($metaData['locations']) && is_array($metaData['locations']) && !empty($metaData['locations'])) {
                            $locations_schema = [];
                            foreach ($metaData['locations'] as $position => $location) {
                                if ($location['type']) {
                                    $location_schema = array(
                                        "@type"   => $KcSeoWPSchema->sanitizeOutPut($location['type']),
                                        'name'    => !empty($location['name']) ? $KcSeoWPSchema->sanitizeOutPut($location['name']) : "",
                                        'url'     => !empty($location['url']) ? $KcSeoWPSchema->sanitizeOutPut($location['url'], 'url') : '',
                                        "address" => [
                                            "@type" => "PostalAddress",
                                        ]
                                    );
                                    if (!empty($location['id'])) {
                                        $location_schema['@id'] = $KcSeoWPSchema->sanitizeOutPut($location['id']);
                                    }
                                    if (!empty($location['image'])) {
                                        $img = $KcSeoWPSchema->imageInfo(absint($location['image']));
                                        $location_schema["image"] = $KcSeoWPSchema->sanitizeOutPut($img['url'], 'url');
                                    }
                                    if (!empty($location['url'])) {
                                        $location_schema['url'] = $KcSeoWPSchema->sanitizeOutPut($location['url'], 'url');
                                    }
                                    if (!empty($location['address_street'])) {
                                        $location_schema['address']['streetAddress'] = $KcSeoWPSchema->sanitizeOutPut($location['address_street']);
                                    }
                                    if (!empty($location['address_locality'])) {
                                        $location_schema['address']['addressLocality'] = $KcSeoWPSchema->sanitizeOutPut($location['address_locality']);
                                    }
                                    if (!empty($location['address_post_code'])) {
                                        $location_schema['address']['postalCode'] = $KcSeoWPSchema->sanitizeOutPut($location['address_post_code']);
                                    }
                                    if (!empty($location['address_region'])) {
                                        $location_schema['address']['addressRegion'] = $KcSeoWPSchema->sanitizeOutPut($location['address_region']);
                                    }
                                    if (!empty($location['address_country'])) {
                                        $location_schema['address']['addressCountry'] = $KcSeoWPSchema->sanitizeOutPut($location['address_country']);
                                    }
                                    if (!empty($location['priceRange'])) {
                                        $location_schema["priceRange"] = $KcSeoWPSchema->sanitizeOutPut($location['priceRange']);
                                    }
                                    if (!empty($location['telephone'])) {
                                        $location_schema["telephone"] = $KcSeoWPSchema->sanitizeOutPut($location['telephone']);
                                    }
                                    array_push($locations_schema, $location_schema);
                                }

                            }
                            if (count($locations_schema) === 1) {
                                $announcement['announcementLocation'] = $locations_schema[0];
                            } else {
                                $announcement['announcementLocation'] = $locations_schema;
                            }
                        }
                        $html .= $this->get_jsonEncode(apply_filters('kcseo_snippet_item_list', $announcement, $metaData));
                        break;
                    default:
                        break;
                }

            }

            return $html;
        }

        function get_field($data) {
            $html = null;
            global $KcSeoWPSchema;
            $id = $data['id'];
            $name = $data['name'];
            $value = $data['value'];
            $attr = !empty($data['attr']) ? $data['attr'] : null;

            $class = isset($data['class']) ? ($data['class'] ? $data['class'] : null) : null;
            $require = (isset($data['required']) ? ($data['required'] ? sprintf('<span data-kcseo-tooltip="%s" class="required">*</span>', __("Required", "wp-seo-structured-data-schema-pro")) : null) : null);
            $recommended = (isset($data['recommended']) ? ($data['recommended'] ? sprintf('<span data-kcseo-tooltip="%s" class="recommended">*</span>', __("Recommended", "wp-seo-structured-data-schema-pro")) : null) : null);
            $title = (isset($data['title']) ? ($data['title'] ? $data['title'] : null) : null);
            $desc = (isset($data['desc']) ? ($data['desc'] ? $data['desc'] : null) : null);
            $holderClass = (!empty($data['holderClass']) ? $data['holderClass'] : null);

            switch ($data['type']) {
                case 'checkbox':
                    $checked = ($value ? "checked" : null);
                    $html .= "<div class='kSeo-checkbox-wrapper'>";
                    $html .= "<label for='{$id}'><input type='checkbox' id='{$id}' class='{$class}' name='{$name}' {$checked} value='1' /> Enable</label>";
                    $html .= "</div>";
                    break;
                case 'text':
                    $html .= "<input type='text' id='{$id}' class='{$class}' {$attr} name='{$name}' value='" . esc_html($value) . "' />";
                    break;
                case 'number':
                    if ($data['fieldId'] == 'price') {
                        $html .= "<input type='number' step='any' id='{$id}' class='{$class}'  {$attr} name='{$name}' value='" . esc_attr($value) . "' />";
                    } else {
                        $html .= "<input type='number' id='{$id}' class='{$class}' name='{$name}'  {$attr} value='" . esc_attr($value) . "' />";
                    }
                    break;
                case 'textarea':
                    $html .= "<textarea id='{$id}' class='{$class}' {$attr} name='{$name}' >" . wp_kses($value,
                            array()) . "</textarea>";
                    break;
                case 'image':
                    $html .= '<div class="kSeo-image">';
                    $ImageId = !empty($value) ? absint($value) : 0;
                    $image = $ingInfo = null;
                    if ($ImageId) {
                        $image = wp_get_attachment_image($ImageId, "thumbnail");
                        $imgData = $KcSeoWPSchema->imageInfo($ImageId);
                        $ingInfo .= "<span><strong>URL: </strong>{$imgData['url']}</span>";
                        $ingInfo .= "<span><strong>Width: </strong>{$imgData['width']}px</span>";
                        $ingInfo .= "<span><strong>Height: </strong>{$imgData['height']}px</span>";
                    }
                    $html .= "<div class='kSeo-image-wrapper'>";
                    $html .= '<span class="kSeoImgAdd"><span class="dashicons dashicons-plus-alt"></span></span>';
                    $html .= '<span class="kSeoImgRemove ' . ($image ? null : "kSeo-hidden") . '"><span class="dashicons dashicons-trash"></span></span>';
                    $html .= '<div class="kSeo-image-preview">' . $image . '</div>';
                    $html .= "<input type='hidden' name='{$name}' value='" . absint($ImageId) . "' />";
                    $html .= "</div>";
                    $html .= "<div class='image-info'>{$ingInfo}</div>";
                    $html .= '</div>';
                    break;
                case 'select':
                    $html .= "<select name='{$name}'  {$attr} class='select2 {$class}' id='{$id}'>";
                    if (!empty($data['empty'])) {
                        $html .= "<option value=''>{$data['empty']}</option>";
                    }
                    if (!empty($data['options']) && is_array($data['options'])) {
                        if ($this->isAssoc($data['options'])) {
                            foreach ($data['options'] as $optKey => $optValue) {
                                $slt = ($optKey == $value ? "selected" : null);
                                $html .= "<option value='" . esc_attr($optKey) . "' {$slt}>" . esc_html($optValue) . "</option>";
                            }
                        } else {
                            foreach ($data['options'] as $optValue) {
                                $slt = ($optValue == $value ? "selected" : null);
                                $html .= "<option value='" . esc_attr($optValue) . "' {$slt}>" . esc_html($optValue) . "</option>";
                            }
                        }
                    }
                    $html .= "</select>";
                    break;
                case 'schema_type':
                    $html .= "<select name='{$name}' class='select2 {$class}' id='{$id}'>";
                    if (!empty($data['empty'])) {
                        $html .= "<option value=''>{$data['empty']}</option>";
                    }

                    foreach ($data['options'] as $key => $site) {
                        if (is_array($site)) {
                            $slt = ($key == $value ? "selected" : null);
                            $html .= "<option value='$key' $slt>&nbsp;&nbsp;&nbsp;$key</option>";
                            foreach ($site as $inKey => $inSite) {
                                if (is_array($inSite)) {
                                    $slt = ($inKey == $value ? "selected" : null);
                                    $html .= "<option value='$inKey' $slt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$inKey</option>";
                                    foreach ($inSite as $inInKey => $inInSite) {
                                        if (is_array($inInSite)) {
                                            $slt = ($inInKey == $value ? "selected" : null);
                                            $html .= "<option value='$inInKey' $slt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$inInKey</option>";
                                            foreach ($inInSite as $iSite) {
                                                $slt = ($iSite == $value ? "selected" : null);
                                                $html .= "<option value='$iSite' $slt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$iSite</option>";
                                            }
                                        } else {
                                            $slt = ($inInSite == $value ? "selected" : null);
                                            $html .= "<option value='$inInSite' $slt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$inInSite</option>";
                                        }
                                    }
                                } else {
                                    $slt = ($inSite == $value ? "selected" : null);
                                    $html .= "<option value='$inSite' $slt>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$inSite</option>";
                                }
                            }
                        } else {
                            $slt = ($site == $value ? "selected" : null);
                            $html .= "<option value='$site' $slt>$site</option>";
                        }
                    }
                    $html .= "</select>";
                    break;
                default:
                    $html .= "<input id='{$id}' type='{$data['type']}' {$attr} value='" . esc_attr($value) . "' name='$name' />";
                    break;

            }
            $label = "<label class='field-label' for='{$id}'>{$title}{$require}{$recommended}</label>";
            $field_html = sprintf('<div class="field-content" id="%s-content">%s<p class="description">%s</div>', $id, $html, $desc);
            if ($data['type'] == 'heading') {
                $holderClass .= ' kcseo-heading-container';
                $label = '';
                $field_html = sprintf('<div class="kcseo-section-title-wrap">%s%s</div>',
                    $title ? sprintf('<h5>%s</h5>', $title) : '',
                    $desc ? sprintf('<p class="description">%s</p>', $desc) : null
                );
            }

            $html = sprintf('<div class="field-container %s" id="%s-container">%s%s</div>',
                $holderClass,
                $id,
                $label,
                $field_html
            );

            return $html;
        }

        function get_jsonEncode($data = array()) {
            $html = null;
            /** @var TYPE_NAME $data */
            if (!empty($data) && is_array($data)) {
                $html .= '<script type="application/ld+json">' . json_encode($data,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
            }

            return $html;
        }

        function imgInfo($url = null) {
            $img = array();
            if ($url) {
                $imgA = @getimagesize($url);
                if (is_array($imgA) && !empty($imgA)) {
                    $img['width'] = $imgA[0];
                    $img['height'] = $imgA[1];
                } else {
                    $img['width'] = 0;
                    $img['height'] = 0;
                }
            }

            return $img;
        }

        function isAssoc($array) {
            $keys = array_keys($array);

            return $keys !== array_keys($keys);
        }


    }
endif;