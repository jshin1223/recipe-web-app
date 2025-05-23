-- Drop existing tables if they exist
DROP TABLE IF EXISTS ratings;
DROP TABLE IF EXISTS favourites;
DROP TABLE IF EXISTS recipe_steps;
DROP TABLE IF EXISTS recipe_ingredients;
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create recipes table
CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    prep_time VARCHAR(100),
    cook_time VARCHAR(100),
    serve VARCHAR(100),
    dietary VARCHAR(255),
    recipe_tips TEXT,
    image_url VARCHAR(255) DEFAULT NULL,
    source_url VARCHAR(255) DEFAULT NULL,
    prep_time_min INT,
    cook_time_min INT,
    serves_min INT,
    audio_filename VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Create recipe_ingredients table
CREATE TABLE IF NOT EXISTS recipe_ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    ingredient VARCHAR(255) NOT NULL,
    quantity VARCHAR(100),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id)
);

-- Create recipe_steps table
CREATE TABLE IF NOT EXISTS recipe_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    step_number INT NOT NULL,
    instruction TEXT NOT NULL,
    time_minutes INT,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id)
);

-- Create favourites table
CREATE TABLE favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipe_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id),
    UNIQUE (user_id, recipe_id)  -- Ensures each user can only favourite a recipe once
);


-- Create ratings table
CREATE TABLE IF NOT EXISTS ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    recipe_id INT NOT NULL,
    difficulty INT NOT NULL,
    aesthetics INT NOT NULL,
    taste INT NOT NULL,
    overall INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id)
);

-- Insert sample users (plain passwords for now)
INSERT INTO users (name, email, password) VALUES 
('Alice', 'alice@example.com', '9999'),
('Bob', 'bob@example.com', '9999'),
('Test', 'test@test.com', '9999');

-- Insert Spaghetti Bolognese Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Spaghetti Bolognese', 'Once you’ve got this grown-up spag bol going the hob will do the rest. Any leftovers will taste even better the next day.', 'less than 30 mins', '1 to 2 hours', 'Serves 6-8', 'Egg-free, Nut-free', 'You can make a veggie version of this recipe by substituting soya mince or Quorn for the meat, adding it to the sauce halfway through cooking. Or simply add lots of diced vegetables to the onions, such as courgettes, carrots, peppers and aubergines.', 1);

-- Get the ID of Spaghetti Bolognese
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Spaghetti Bolognese
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Olive oil or sun-dried tomato oil from the jar', '2 tbsp'),
(@recipe_id, 'Smoked streaky bacon', '6 rashers, chopped'),
(@recipe_id, 'Large onions', '2, chopped'),
(@recipe_id, 'Garlic cloves', '3, crushed'),
(@recipe_id, 'Lean minced beef', '1kg'),
(@recipe_id, 'Red wine', '2 large glasses'),
(@recipe_id, 'Cans chopped tomatoes', '2 x 400g'),
(@recipe_id, 'Jar antipasti marinated mushrooms', '1 x 290g, drained'),
(@recipe_id, 'Bay leaves', '2 fresh or dried'),
(@recipe_id, 'Dried oregano', '1 tsp'),
(@recipe_id, 'Dried thyme', '1 tsp'),
(@recipe_id, 'Balsamic vinegar', 'Drizzle'),
(@recipe_id, 'Sun-dried tomato halves in oil', '12-14'),
(@recipe_id, 'Fresh basil leaves', 'A good handful, torn into small pieces'),
(@recipe_id, 'Dried spaghetti', '800g-1kg'),
(@recipe_id, 'Freshly grated parmesan', 'To serve');

-- Insert preparation steps for Spaghetti Bolognese
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'Heat the oil in a large, heavy-based saucepan and fry the bacon until golden over a medium heat. Add the onions and garlic, frying until softened. Increase the heat and add the minced beef. Fry it until it has browned, breaking down any chunks of meat with a wooden spoon. Pour in the wine and boil until it has reduced in volume by about a third. Reduce the temperature and stir in the tomatoes, drained mushrooms, bay leaves, oregano, thyme and balsamic vinegar.', 10),
(@recipe_id, 2, 'Either blitz the sun-dried tomatoes in a small blender with a little of the oil to loosen, or just finely chop before adding to the pan. Season well with salt and pepper. Cover with a lid and simmer the Bolognese sauce over a gentle heat for 1-1½ hours until it\'s rich and thickened, stirring occasionally. At the end of the cooking time, stir in the basil and add any extra seasoning if necessary.', 90),
(@recipe_id, 3, 'Remove from the heat to "settle" while you cook the spaghetti in plenty of boiling salted water (for the time stated on the packet). Drain and divide between warmed plates. Scatter a little parmesan over the spaghetti before adding a good ladleful of the Bolognese sauce, finishing with a scattering of more cheese and a twist of black pepper.', 15);

-- Insert ratings for Spaghetti Bolognese
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(1, @recipe_id, 4, 5, 5, 4);  -- Alice rated Spaghetti Bolognese

-- Insert Vegan Pancakes Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Vegan Pancakes', 'Try this vegan fluffy American pancake recipe for a perfect start to the day. Serve these pancakes with fresh berries, maple syrup or chocolate sauce for a really luxurious start to the day.', 'less than 30 mins', '10 to 30 mins', 'Serves 2', 'Dairy-free, Egg-free, Pregnancy-friendly, Vegan, Vegetarian', 'Whipped coconut cream is good with these too, but it must be well chilled before whipping. You can keep the pancakes warm in a low oven while you make the full batch.', 2);

-- Get the ID of Vegan Pancakes
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Vegan Pancakes
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Self-raising flour', '125g/4½oz'),
(@recipe_id, 'Caster sugar', '2 tbsp'),
(@recipe_id, 'Baking powder', '1 tsp'),
(@recipe_id, 'Sea salt', 'Pinch'),
(@recipe_id, 'Soya milk', '150ml/5fl oz'),
(@recipe_id, 'Vanilla extract', '¼ tsp'),
(@recipe_id, 'Sunflower oil', '4 tsp for frying');

-- Insert preparation steps for Vegan Pancakes
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'Put the flour, sugar, baking powder and salt in a bowl and mix thoroughly. Add the milk and vanilla extract and mix with a whisk until smooth.', 5),
(@recipe_id, 2, 'Place a large non-stick frying pan over a medium heat. Add 2 teaspoons of the oil and wipe around the pan with a heatproof brush or carefully using a thick wad of kitchen paper.', 5),
(@recipe_id, 3, 'Once the pan is hot, pour a small ladleful (around two dessert spoons) of the batter into one side of the pan and spread with the back of the spoon until around 10cm/4in in diameter. Make a second pancake in exactly the same way, greasing the pan with the remaining oil before adding the batter.', 10),
(@recipe_id, 4, 'Cook for about a minute, or until bubbles are popping on the surface and just the edges look dry and slightly shiny. Quickly and carefully flip over and cook on the other side for a further minute, or until light, fluffy and pale golden brown. If you turn the pancakes too late, they will be too set to rise evenly. You can always flip again if you need the first side to go a little browner.', 2),
(@recipe_id, 5, 'Transfer to a plate and keep warm in a single layer (so they don’t get squished) on a baking tray in a low oven while the rest of the pancakes are cooked in exactly the same way. Serve with your preferred toppings.', 5);

-- Insert ratings for Vegan Pancakes
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(2, @recipe_id, 3, 4, 4, 4);  -- Bob rated Vegan Pancakes

-- Insert Healthy Pizza Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Healthy Pizza', 'No yeast required for this easy, healthy pizza, topped with colourful vegetables that\'s ready in 30 minutes. This is a great recipe if you want to feed the kids in a hurry!', 'less than 30 mins', '10 to 30 mins', 'Serves 2', 'Egg-free, Healthy, Nut-free, Pregnancy-friendly, Vegetarian', 'You can use any cheese you like for this pizza – it’s also a great way to use up a mix of odds and ends from the fridge. Make two pizzas instead of one large pizza if you like. Any leftover passata can be used for pasta sauces, stews or curries. It freezes well for up to 4 months.', 1);

-- Get the ID of Healthy Pizza
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Healthy Pizza
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Self-raising brown or self-raising wholemeal flour', '125g/4½oz'),
(@recipe_id, 'Fine sea salt', 'Pinch'),
(@recipe_id, 'Full-fat plain yoghurt', '125g/4½oz'),
(@recipe_id, 'Yellow or orange pepper', '1, sliced'),
(@recipe_id, 'Courgette', '1, sliced'),
(@recipe_id, 'Red onion', '1, sliced'),
(@recipe_id, 'Extra virgin olive oil', '1 tbsp'),
(@recipe_id, 'Dried chilli flakes', '½ tsp'),
(@recipe_id, 'Grated mozzarella or cheddar', '50g'),
(@recipe_id, 'Fresh basil leaves', 'To serve');

-- Insert preparation steps for Healthy Pizza
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'Preheat the oven to 220C/200C Fan/Gas 7.', 5),
(@recipe_id, 2, 'To prepare the topping, put the pepper, courgette, red onion and oil in a bowl, season with lots of black pepper and mix together. Scatter the vegetables over a large baking tray and roast for 15 minutes.', 15),
(@recipe_id, 3, 'Meanwhile, make the pizza base. Mix the flour and salt in a large bowl. Add the yoghurt and 1 tablespoon of cold water and mix with a spoon, then use your hands to form a soft, spongy dough. Turn out onto a lightly floured surface and knead for about 1 minute.', 5),
(@recipe_id, 4, 'Using a floured rolling pin, roll the dough into a roughly oval shape, approx. 3mm/⅛in thick, turning regularly. (Ideally, the pizza should be around 30cm/12in long and 20cm/8in wide, but it doesn’t matter if the shape is uneven, it just needs to fit onto the same baking tray that the vegetables were cooked on.)', 10),
(@recipe_id, 5, 'Transfer the roasted vegetables to a bowl. Slide the pizza dough onto the baking tray and bake for 5 minutes. Take the tray out of the oven and turn the dough over. For the tomato sauce, mix the passata with the oregano and spread over the dough. Top with the roasted vegetables, sprinkle with the chilli flakes and then the cheese. Bake the pizza for a further 8–10 minutes, or until the dough is cooked through and the cheese beginning to brown.', 15),
(@recipe_id, 6, 'Season with black pepper, drizzle with a slurp of olive oil and, if you like, scatter fresh basil leaves on top just before serving.', 5);

-- Insert ratings for Healthy Pizza
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(1, @recipe_id, 4, 4, 4, 4);  -- Alice rated Healthy Pizza

-- Insert Easy Lamb Biryani Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Easy Lamb Biryani', 'This lamb biryani is a real centerpiece dish, but it\'s actually easy as anything to make. Serve garnished with pomegranate seeds to make it look really special.', 'Overnight', '1 to 2 hours', 'Serves 6–8', 'Egg-free, Gluten-free, Pregnancy-friendly', 'Kashmiri red chilli powder is quite mild with a slightly smoky flavour that really adds to the dish.', 2);

-- Get the ID of Easy Lamb Biryani
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Easy Lamb Biryani
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Vegetable oil', '5 tbsp'),
(@recipe_id, 'Onions', '2 finely sliced'),
(@recipe_id, 'Greek or natural yoghurt', '200g/7oz'),
(@recipe_id, 'Finely grated ginger', '4 tbsp'),
(@recipe_id, 'Finely grated garlic', '3 tbsp'),
(@recipe_id, 'Kashmiri red chilli powder', '1–2 tsp'),
(@recipe_id, 'Ground cumin', '5 tsp'),
(@recipe_id, 'Ground cardamom seeds', '1 tsp'),
(@recipe_id, 'Sea salt', '4 tsp'),
(@recipe_id, 'Lime juice', '1 lime'),
(@recipe_id, 'Coriander leaves', '30g/1oz, finely chopped'),
(@recipe_id, 'Mint leaves', '30g/1oz, finely chopped'),
(@recipe_id, 'Green chillies', '3–4, finely chopped'),
(@recipe_id, 'Boneless lamb tenderloin', '800g/1lb 12oz, cut into bite-sized pieces'),
(@recipe_id, 'Double cream', '4 tbsp'),
(@recipe_id, 'Full-fat milk', '1½ tbsp'),
(@recipe_id, 'Saffron strands', '1 tsp'),
(@recipe_id, 'Basmati rice', '400g/14oz'),
(@recipe_id, 'Pomegranate seeds', '2 tbsp (optional)');

-- Insert preparation steps for Easy Lamb Biryani
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'Heat the oil in a non-stick frying pan over a medium heat. Add the onions and stir-fry for 15–18 minutes, or until lightly browned and crispy.', 15),
(@recipe_id, 2, 'Put half the onions in a non-metallic mixing bowl with the yoghurt, ginger, garlic, chilli powder, cumin, cardamom, half of the salt, the lime juice, half of the chopped coriander and mint and the green chillies. Stir well to combine. Set aside the remaining coriander and mint for layering the biryani.', 10),
(@recipe_id, 3, 'Add the lamb to the mixture and stir to coat evenly. Cover and marinade in the fridge for 6–8 hours, or overnight if possible.', 480),
(@recipe_id, 4, 'Preheat the oven to 240C/Fan 220C/Gas 9.', 10),
(@recipe_id, 5, 'Heat the cream and milk in a small saucepan, add the saffron, remove from the heat and leave to infuse for 30 minutes.', 30),
(@recipe_id, 6, 'Cook the rice in a large saucepan in plenty of boiling water with the remaining salt for 6–8 minutes, or until it is just cooked, but still has a bite. Drain the rice.', 10),
(@recipe_id, 7, 'Spread half of the lamb mixture evenly in a wide, heavy-based casserole and cover with a layer of half the rice. Sprinkle over half of the reserved onions and half of the reserved coriander and mint. Sprinkle over half of the saffron mixture. Repeat with the remaining lamb, rice, onions, herbs and saffron mixture.', 10),
(@recipe_id, 8, 'Cover with a tight fitting lid, turn down the oven to 200C/Fan 180C/Gas 6 and cook for 1 hour. Remove and allow to stand for 15–20 minutes before serving. Garnish with pomegranate seeds if desired.', 60);

-- Insert ratings for Easy Lamb Biryani
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(1, @recipe_id, 4, 4, 5, 5);  -- Alice rated Easy Lamb Biryani

-- Insert Couscous Salad Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Couscous Salad', 'A nutritious and satisfying vegan couscous salad packed with colour, flavour and texture, from dried cranberries, pistachios and pine nuts.', 'less than 30 mins', 'less than 10 mins', 'Serves 6', 'Egg-free, Vegan, Vegetarian', 'Couscous salads are great to make ahead for easy entertaining or weekday lunches. It will keep well for a few days in the fridge.', 1);

-- Get the ID of Couscous Salad
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Couscous Salad
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Couscous', '225g/8oz'),
(@recipe_id, 'Preserved lemons', '8 small, chopped'),
(@recipe_id, 'Dried cranberries', '180g/6⅓oz'),
(@recipe_id, 'Pine nuts', '120g/4⅓oz, toasted'),
(@recipe_id, 'Pistachio nuts', '160g/5¾oz, chopped'),
(@recipe_id, 'Olive oil', '125ml/4fl oz'),
(@recipe_id, 'Flatleaf parsley', '60g/2¼oz, chopped'),
(@recipe_id, 'Garlic cloves', '4, crushed'),
(@recipe_id, 'Red wine vinegar', '4 tbsp'),
(@recipe_id, 'Red onion', '1, finely chopped'),
(@recipe_id, 'Salt', '1 tsp or to taste'),
(@recipe_id, 'Rocket leaves', '80g/2¾oz');

-- Insert preparation steps for Couscous Salad
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'In a large bowl mix all the ingredients together except the rocket, then taste and adjust the seasoning, adding more salt if necessary. Toss in the rocket and serve immediately.', 10);

-- Insert ratings for Couscous Salad
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(1, @recipe_id, 3, 4, 4, 4);  -- Alice rated Couscous Salad

-- Insert Plum Clafoutis Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Plum Clafoutis', 'Halved plums are covered in a light batter and then baked in the oven to make this traditional French dessert. British plums are at their best in September, so make the most of them then and try this simple pud.', 'less than 30 mins', '30 mins to 1 hour', 'Serves 4-6', 'Vegetarian', 'Dust with icing sugar and serve immediately with cream.', 2);

-- Get the ID of Plum Clafoutis
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Plum Clafoutis
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Milk', '125ml/4½fl oz'),
(@recipe_id, 'Double cream', '125ml/4½fl oz'),
(@recipe_id, 'Vanilla essence', '2-3 drops'),
(@recipe_id, 'Free-range eggs', '4'),
(@recipe_id, 'Caster sugar', '170g/6oz'),
(@recipe_id, 'Plain flour', '1 tbsp'),
(@recipe_id, 'Butter', '30g/1oz'),
(@recipe_id, 'Plums', '500g/1lb 2oz, halved and stoned'),
(@recipe_id, 'Brown sugar', '2 tbsp'),
(@recipe_id, 'Flaked almonds', '30g/1oz (optional)'),
(@recipe_id, 'Icing sugar', 'For dusting'),
(@recipe_id, 'Double cream', 'To serve');

-- Insert preparation steps for Plum Clafoutis
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'Preheat the oven to 180C/350F/Gas 4.', 5),
(@recipe_id, 2, 'Pour the milk, cream and vanilla into a pan and boil for a minute. Remove from the heat and set aside to cool.', 5),
(@recipe_id, 3, 'Tip the eggs and sugar into a bowl and beat together until light and fluffy. Fold the flour into the mixture, a little at a time.', 10),
(@recipe_id, 4, 'Pour the cooled milk and cream onto the egg and sugar mixture, whisking lightly. Set aside.', 5),
(@recipe_id, 5, 'Place a little butter into an ovenproof dish and heat in the oven until foaming. Add the plums and brown sugar and bake for 5 minutes, then pour the batter into the dish and scatter with flaked almonds, if using.', 10),
(@recipe_id, 6, 'Cook in the oven for about 30 minutes, until golden-brown and set but still light and soft inside.', 30);

-- Insert ratings for Plum Clafoutis
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(2, @recipe_id, 3, 4, 4, 4);  -- Bob rated Plum Clafoutis

-- Insert Mango Pie Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Mango Pie', 'This mouthwatering mango dessert is an Indian take on a traditional Thanksgiving pie.', '30 mins to 1 hour', '30 mins to 1 hour', 'Serves 16', 'Egg-free, Nut-free', 'This recipe makes two pies, so halve the ingredients if you\'re not feeding a crowd.', 1);

-- Get the ID of Mango Pie
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Mango Pie
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Digestive biscuits', '280g/10oz'),
(@recipe_id, 'Granulated sugar', '65g/2¼oz'),
(@recipe_id, 'Ground cardamom', '¼ tsp'),
(@recipe_id, 'Unsalted butter', '128g/4½oz, melted'),
(@recipe_id, 'Sea salt', 'Large pinch'),
(@recipe_id, 'Granulated sugar', '100g/3½oz'),
(@recipe_id, 'Powdered gelatine', '2 tbsp plus ¼ tsp'),
(@recipe_id, 'Double cream', '120ml/4fl oz'),
(@recipe_id, 'Cream cheese', '115g/4oz, room temperature'),
(@recipe_id, 'Alfonso mango pulp', '850g tin'),
(@recipe_id, 'Sea salt', 'Large pinch');

-- Insert preparation steps for Mango Pie
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'To make the biscuit base, finely crush the biscuits by putting into a sealed plastic bag and bashing with a rolling pin (alternatively, pulse to crumbs using a food processor). Transfer to a mixing bowl and add the sugar, cardamom and salt, stirring well to combine.', 10),
(@recipe_id, 2, 'Pour the melted butter over the biscuit crumbs and mix, until thoroughly combined. Put half the crumb mixture in a 23cm/9in metal pie tin, and press evenly with your fingers. Build up the sides of the tin, compressing the base as much as possible to prevent it crumbling. Repeat with the rest of the mixture in the second tin.', 15),
(@recipe_id, 3, 'Preheat the oven to 160C/325F/Gas 3. Put the pie bases in the freezer for 15 minutes. Remove and bake for 12 minutes, or until golden brown. Transfer to a wire rack to cool.', 12),
(@recipe_id, 4, 'To make the filling, pour 177ml/6fl oz of cold water into a large bowl. In a separate bowl, mix the gelatine with half the sugar and sprinkle over the water. Leave to stand for a couple of minutes.', 5),
(@recipe_id, 5, 'Meanwhile, whip the cream with the remaining sugar to form medium stiff peaks. Set aside.', 10),
(@recipe_id, 6, 'Heat about a quarter of the mango pulp in a saucepan over a medium-low heat, until just warm. Make sure you do not boil it. Pour into the gelatine mixture and whisk, until well combined. The gelatine should dissolve completely. Gradually whisk in the remaining mango pulp.', 10),
(@recipe_id, 7, 'Beat the cream cheese in a bowl, until soft and smooth. Add to the mango mixture with the salt. Blend the mixture using a hand blender, until completely smooth. Gently tap the bowl on the kitchen counter once or twice to pop any air bubbles.', 10),
(@recipe_id, 8, 'Fold about a quarter of the mango mixture into the whipped cream using a spatula. Repeat with the rest of the cream, until no streaks remain.', 10),
(@recipe_id, 9, 'Divide the filling between the cooled bases, using a rubber spatula to smooth out the filling. Refrigerate overnight, or for at least 5 hours, until firm and chilled.', 300);

-- Insert ratings for Mango Pie
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(1, @recipe_id, 4, 4, 5, 5);  -- Alice rated Mango Pie

-- Insert Mushroom Doner Recipe
INSERT INTO recipes (title, description, prep_time, cook_time, serve, dietary, recipe_tips, created_by) VALUES
('Mushroom Doner', 'A meat-free mushroom ‘doner’ kebab packed with two types of sauces, pickles and veg. A mighty delicious vegetarian dish.', 'less than 30 mins', '10 to 30 mins', 'Serves 4', 'Egg-free, Healthy, Nut-free, Pregnancy-friendly, Vegetarian', 'You can use any vegetables of your choice for garnish.', 2);

-- Get the ID of Mushroom Doner
SET @recipe_id = LAST_INSERT_ID();

-- Insert ingredients for Mushroom Doner
INSERT INTO recipe_ingredients (recipe_id, ingredient, quantity) VALUES 
(@recipe_id, 'Chopped tomatoes', '1 x 400g tin'),
(@recipe_id, 'Rose harissa', '2 tbsp'),
(@recipe_id, 'Caster sugar', '2 tsp'),
(@recipe_id, 'Lemon juice', 'Good squeeze'),
(@recipe_id, 'Onion', '1, thinly sliced'),
(@recipe_id, 'White wine vinegar', '2 level tsp'),
(@recipe_id, 'Flatleaf parsley', '20g/¾oz, finely chopped'),
(@recipe_id, 'Plain yoghurt', '150g/5½oz'),
(@recipe_id, 'Dried mint', '1 heaped tsp'),
(@recipe_id, 'Oyster mushrooms', '500g/1lb 2oz, thinly sliced'),
(@recipe_id, 'Garlic oil', '2 tsp'),
(@recipe_id, 'Sweet paprika', '2 tsp'),
(@recipe_id, 'Ground coriander', '2 heaped tsp'),
(@recipe_id, 'Celery salt', '2 tsp'),
(@recipe_id, 'Garlic granules', '3 tsp'),
(@recipe_id, 'Freshly ground black pepper', '½ tsp'),
(@recipe_id, 'Pitta breads', '4'),
(@recipe_id, 'Shredded cabbage', '¼ small'),
(@recipe_id, 'Tomato', '2, sliced into half moons'),
(@recipe_id, 'Pickled chillies', '4–6 (optional)');

-- Insert preparation steps for Mushroom Doner
INSERT INTO recipe_steps (recipe_id, step_number, instruction, time_minutes) VALUES
(@recipe_id, 1, 'Preheat the oven to 180C/200C Fan/Gas 4.', 5),
(@recipe_id, 2, 'To make the chilli sauce, heat the chopped tomatoes, rose harissa, sugar and lemon juice in a small saucepan over a medium heat. Bring to a gentle boil and cook for 10 minutes, stirring regularly, until reduced to a thick sauce-like consistency. Remove from the heat and set aside to cool. You can blend the sauce until it’s smooth using a hand-blender if you like, or just leave it chunky.', 10),
(@recipe_id, 3, 'For the onion, mix together the onion slices, vinegar and parsley and set aside.', 5),
(@recipe_id, 4, 'To make the yoghurt sauce, mix the yoghurt with the dried mint, season with salt and pepper and set aside.', 5),
(@recipe_id, 5, 'Put the pittas in the oven to warm for 5 minutes.', 5),
(@recipe_id, 6, 'To make the "doner," heat a frying pan over a medium-high heat. Add the mushrooms and dry-fry for 2 minutes, stirring once or twice. Add the garlic oil, paprika, coriander, celery salt, garlic granules and black pepper and quickly coat the mushrooms. Add 2–3 tablespoons of water to the pan and stir-fry for 1 minute before removing from the heat.', 5),
(@recipe_id, 7, 'Split the warmed pitta breads. Spoon a little white cabbage into each pitta and add a little tomato and onion. Divide the mushrooms between the pittas, add a little more cabbage and tomato, then drizzle with the chilli and yoghurt sauces. Serve immediately, topped with the pickled chillies, if using.', 5);

-- Insert ratings for Mushroom Doner
INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES
(2, @recipe_id, 3, 4, 4, 4);  -- Bob rated Mushroom Doner

-- Insert image_url and source_url data for each recipe
UPDATE recipes SET image_url = 'assets/images/spaghetti.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/spaghettibolognese_67868' WHERE id = 1;
UPDATE recipes SET image_url = 'assets/images/vegan_american_pancakes.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/vegan_american_pancakes_76094' WHERE id = 2;
UPDATE recipes SET image_url = 'assets/images/healthy_pizza.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/healthy_pizza_55143' WHERE id = 3;
UPDATE recipes SET image_url = 'assets/images/easy_lamb_biryani.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/easy_lamb_biryani_46729' WHERE id = 4;
UPDATE recipes SET image_url = 'assets/images/couscous_salad.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/dried_fruits_and_nuts_18053' WHERE id = 5;
UPDATE recipes SET image_url = 'assets/images/plumclafoutis.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/plumclafoutis_11536' WHERE id = 6;
UPDATE recipes SET image_url = 'assets/images/mango_pie.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/mango_pie_18053' WHERE id = 7;
UPDATE recipes SET image_url = 'assets/images/mushroom_doner.jpg', source_url = 'https://www.bbc.co.uk/food/recipes/mushroom_doner_22676' WHERE id = 8;

-- add additional data for recipes
-- Spaghetti Bolognese (ID 1)
UPDATE recipes SET 
  prep_time_min = 30,
  cook_time_min = 120,
  serves_min = 6
WHERE id = 1;

-- Vegan Pancakes (ID 2)
UPDATE recipes SET 
  prep_time_min = 30,
  cook_time_min = 30,
  serves_min = 2
WHERE id = 2;

-- Healthy Pizza (ID 3)
UPDATE recipes SET 
  prep_time_min = 30,
  cook_time_min = 30,
  serves_min = 2
WHERE id = 3;

-- Easy Lamb Biryani (ID 4)
UPDATE recipes SET 
  prep_time_min = 720,  -- overnight ≈ 12 hours
  cook_time_min = 120,
  serves_min = 6
WHERE id = 4;

-- Couscous Salad (ID 5)
UPDATE recipes SET 
  prep_time_min = 30,
  cook_time_min = 10,
  serves_min = 6
WHERE id = 5;

-- Plum Clafoutis (ID 6)
UPDATE recipes SET 
  prep_time_min = 30,
  cook_time_min = 60,
  serves_min = 4
WHERE id = 6;

-- Mango Pie (ID 7)
UPDATE recipes SET 
  prep_time_min = 60,
  cook_time_min = 60,
  serves_min = 16
WHERE id = 7;

-- Mushroom Doner (ID 8)
UPDATE recipes SET 
  prep_time_min = 30,
  cook_time_min = 30,
  serves_min = 4
WHERE id = 8;

-- Update each recipe with its corresponding audio file using ID (ordered by ID)
UPDATE recipes SET audio_filename = 'spaghetti.mp3' WHERE id = 1;
UPDATE recipes SET audio_filename = 'vegan.mp3' WHERE id = 2;
UPDATE recipes SET audio_filename = 'healthy_pizza.mp3' WHERE id = 3;
UPDATE recipes SET audio_filename = 'easy_lamb.mp3' WHERE id = 4;
UPDATE recipes SET audio_filename = 'couscous_salad.mp3' WHERE id = 5;
UPDATE recipes SET audio_filename = 'plum.mp3' WHERE id = 6;
UPDATE recipes SET audio_filename = 'mango.mp3' WHERE id = 7;
UPDATE recipes SET audio_filename = 'mushroom.mp3' WHERE id = 8;