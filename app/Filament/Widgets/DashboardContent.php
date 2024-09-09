<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardContent extends Widget
{
    protected static ?int $sort = 5;

    protected static string $view = 'filament.widgets.dashboard-content';
    public $reference;
    public $text;

    protected function getViewData(): array
    {
        $verses = [
            "John 3:16" => "For God so loved the world that he gave his one and only Son, that whoever believes in him shall not perish but have eternal life.",
            "Proverbs 3:5-6" => "Trust in the LORD with all your heart and lean not on your own understanding; in all your ways submit to him, and he will make your paths straight.",
            "Philippians 4:13" => "I can do all this through him who gives me strength.",
            "Psalm 23:1" => "The LORD is my shepherd, I lack nothing.",
            "Romans 8:28" => "And we know that in all things God works for the good of those who love him, who have been called according to his purpose.",
            "Genesis 1:1" => "In the beginning God created the heavens and the earth.",
            "Exodus 14:14" => "The Lord will fight for you; you need only to be still.",
            "Deuteronomy 31:6" => "Be strong and courageous. Do not be afraid or terrified because of them, for the Lord your God goes with you; he will never leave you nor forsake you.",
            "Joshua 1:9" => "Have I not commanded you? Be strong and courageous. Do not be afraid; do not be discouraged, for the Lord your God will be with you wherever you go.",
            "1 Samuel 16:7" => "But the Lord said to Samuel, 'Do not consider his appearance or his height, for I have rejected him. The Lord does not look at the things people look at. People look at the outward appearance, but the Lord looks at the heart.'",
            "Psalm 23:1" => "The Lord is my shepherd, I lack nothing.",
            "Psalm 46:10" => "Be still, and know that I am God; I will be exalted among the nations, I will be exalted in the earth.",
            "Proverbs 3:5-6" => "Trust in the Lord with all your heart and lean not on your own understanding; in all your ways submit to him, and he will make your paths straight.",
            "Isaiah 40:31" => "But those who hope in the Lord will renew their strength. They will soar on wings like eagles; they will run and not grow weary, they will walk and not be faint.",
            "Jeremiah 29:11" => "For I know the plans I have for you, declares the Lord, plans to prosper you and not to harm you, plans to give you hope and a future.",
            "Matthew 5:16" => "In the same way, let your light shine before others, that they may see your good deeds and glorify your Father in heaven.",
            "Matthew 6:33" => "But seek first his kingdom and his righteousness, and all these things will be given to you as well.",
            "John 3:16" => "For God so loved the world that he gave his one and only Son, that whoever believes in him shall not perish but have eternal life.",
            "John 14:6" => "Jesus answered, 'I am the way and the truth and the life. No one comes to the Father except through me.'",
            "Romans 8:28" => "And we know that in all things God works for the good of those who love him, who have been called according to his purpose.",
            "Romans 12:2" => "Do not conform to the pattern of this world, but be transformed by the renewing of your mind. Then you will be able to test and approve what God's will is—his good, pleasing and perfect will.",
            "1 Corinthians 13:4-5" => "Love is patient, love is kind. It does not envy, it does not boast, it is not proud. It does not dishonor others, it is not self-seeking, it is not easily angered, it keeps no record of wrongs.",
            "Ephesians 2:8-9" => "For it is by grace you have been saved, through faith—and this is not from yourselves, it is the gift of God— not by works, so that no one can boast.",
            "Philippians 4:13" => "I can do all this through him who gives me strength.",
            "Colossians 3:23" => "Whatever you do, work at it with all your heart, as working for the Lord, not for human masters.",
            "2 Timothy 1:7" => "For the Spirit God gave us does not make us timid, but gives us power, love and self-discipline.",
            "Hebrews 11:1" => "Now faith is confidence in what we hope for and assurance about what we do not see.",
            "James 1:22" => "Do not merely listen to the word, and so deceive yourselves. Do what it says.",
            "1 Peter 5:7" => "Cast all your anxiety on him because he cares for you.",
            "1 John 4:19" => "We love because he first loved us.",
            "Genesis 50:20" => "You intended to harm me, but God intended it for good to accomplish what is now being done, the saving of many lives.",
            "Exodus 20:12" => "Honor your father and your mother, so that you may live long in the land the Lord your God is giving you.",
            "Leviticus 19:18" => "Do not seek revenge or bear a grudge against anyone among your people, but love your neighbor as yourself. I am the Lord.",
            "Numbers 6:24-26" => "The Lord bless you and keep you; the Lord make his face shine on you and be gracious to you; the Lord turn his face toward you and give you peace.",
            "Deuteronomy 6:5" => "Love the Lord your God with all your heart and with all your soul and with all your strength.",
            "Ruth 1:16" => "But Ruth replied, 'Don't urge me to leave you or to turn back from you. Where you go I will go, and where you stay I will stay. Your people will be my people and your God my God.'",
            "1 Kings 3:9" => "So give your servant a discerning heart to govern your people and to distinguish between right and wrong. For who is able to govern this great people of yours?",
            "Job 19:25" => "I know that my redeemer lives, and that in the end he will stand on the earth.",
            "Psalm 19:1" => "The heavens declare the glory of God; the skies proclaim the work of his hands.",
            "Psalm 119:105" => "Your word is a lamp for my feet, a light on my path.",
            "Proverbs 22:6" => "Start children off on the way they should go, and even when they are old they will not turn from it.",
            "Ecclesiastes 3:1" => "There is a time for everything, and a season for every activity under the heavens.",
            "Isaiah 53:5" => "But he was pierced for our transgressions, he was crushed for our iniquities; the punishment that brought us peace was on him, and by his wounds we are healed.",
            "Lamentations 3:22-23" => "Because of the Lord's great love we are not consumed, for his compassions never fail. They are new every morning; great is your faithfulness.",
            "Micah 6:8" => "He has shown you, O mortal, what is good. And what does the Lord require of you? To act justly and to love mercy and to walk humbly with your God.",
            "Habakkuk 3:17-18" => "Though the fig tree does not bud and there are no grapes on the vines, though the olive crop fails and the fields produce no food, though there are no sheep in the pen and no cattle in the stalls, yet I will rejoice in the Lord, I will be joyful in God my Savior.",
            "Matthew 28:19-20" => "Therefore go and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit, and teaching them to obey everything I have commanded you. And surely I am with you always, to the very end of the age.",
            "Luke 6:31" => "Do to others as you would have them do to you.",
            "John 8:32" => "Then you will know the truth, and the truth will set you free.",
            "Acts 1:8" => "But you will receive power when the Holy Spirit comes on you; and you will be my witnesses in Jerusalem, and in all Judea and Samaria, and to the ends of the earth.",
            "Romans 5:8" => "But God demonstrates his own love for us in this: While we were still sinners, Christ died for us.",
            "1 Corinthians 10:13" => "No temptation has overtaken you except what is common to mankind. And God is faithful; he will not let you be tempted beyond what you can bear. But when you are tempted, he will also provide a way out so that you can endure it.",
            "Galatians 5:22-23" => "But the fruit of the Spirit is love, joy, peace, forbearance, kindness, goodness, faithfulness, gentleness and self-control. Against such things there is no law.",
            "Ephesians 6:12" => "For our struggle is not against flesh and blood, but against the rulers, against the authorities, against the powers of this dark world and against the spiritual forces of evil in the heavenly realms.",
            "Philippians 2:3-4" => "Do nothing out of selfish ambition or vain conceit. Rather, in humility value others above yourselves, not looking to your own interests but each of you to the interests of the others.",
        ];

        $randomKey = array_rand($verses);
        $this->reference = $randomKey;
        $this->text = $verses[$randomKey];

        return [
            'reference' => $this->reference,
            'text' => $this->text,
        ];
    }

    public function refresh(): void
    {
        $this->getViewData();
    }
}
