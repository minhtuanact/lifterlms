INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (18, 'WhatsApp', 'https://web.whatsapp.com/send?text={url}', 'whatsapp', '#43C353', '#ffffff', 0)
  ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);
