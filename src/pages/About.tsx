
import { useEffect } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Heart, Droplet, Clock, AlertCircle, CheckCircle, Users, Hourglass } from "lucide-react";

const About = () => {
  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  const faqItems = [
    {
      question: "Who can donate blood?",
      answer: "Generally, anyone who is at least 18 years old, weighs more than 50 kg, and is in good health can donate blood. However, specific eligibility criteria may vary based on regional regulations and medical guidelines."
    },
    {
      question: "How often can I donate blood?",
      answer: "Most healthy individuals can donate whole blood every 3 months (or 12 weeks). For specific components like platelets, you may be able to donate more frequently."
    },
    {
      question: "Is blood donation painful?",
      answer: "Most donors report only a brief pinch when the needle is inserted. The actual donation process is relatively painless, though some donors may experience mild discomfort."
    },
    {
      question: "How long does a blood donation take?",
      answer: "The entire process usually takes about 45-60 minutes, but the actual blood collection typically only takes 8-10 minutes."
    },
    {
      question: "How much blood is taken during a donation?",
      answer: "A standard whole blood donation is approximately 450-500 ml, which is about 10% of your total blood volume. Your body replaces this volume within 24-48 hours."
    },
    {
      question: "What happens to my blood after donation?",
      answer: "After collection, your blood is tested, processed, and separated into components (red cells, platelets, plasma). These components are then distributed to hospitals where they're needed for patients."
    },
  ];

  const bloodDonationProcess = [
    {
      title: "Registration",
      description: "Complete a registration form and present identification",
      icon: <Users className="h-6 w-6" />
    },
    {
      title: "Health Screening",
      description: "Brief examination including blood pressure, pulse, and hemoglobin test",
      icon: <CheckCircle className="h-6 w-6" />
    },
    {
      title: "Donation",
      description: "The actual blood collection takes only about 8-10 minutes",
      icon: <Droplet className="h-6 w-6" />
    },
    {
      title: "Recovery",
      description: "Rest and refreshments for 10-15 minutes before leaving",
      icon: <Hourglass className="h-6 w-6" />
    }
  ];

  return (
    <div className="min-h-screen bg-white dark:bg-gray-950">
      <Navbar />
      
      <main className="pt-28 pb-16 animate-fade-in">
        {/* Hero Section */}
        <section className="bg-gradient-to-b from-blood/10 to-transparent px-6 py-16">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-flex items-center gap-2 bg-blood/10 text-blood px-4 py-1.5 rounded-full text-sm font-medium mb-6">
              <Heart className="h-4 w-4" />
              <span>About Blood Donation</span>
            </div>
            <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
              The gift of blood is the gift of life
            </h1>
            <p className="text-lg text-muted-foreground max-w-3xl mx-auto">
              Every 2 seconds, someone needs blood. Your donation can save up to three lives, making it one of the most impactful ways to help others in your community.
            </p>
          </div>
        </section>

        {/* Stats Section */}
        <section className="px-6 py-16">
          <div className="max-w-7xl mx-auto">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="bg-white dark:bg-gray-900 rounded-xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm text-center">
                <div className="inline-flex items-center justify-center w-16 h-16 bg-blood/10 rounded-full mb-6">
                  <Droplet className="h-8 w-8 text-blood" />
                </div>
                <h3 className="text-4xl font-bold mb-2">38,000</h3>
                <p className="text-muted-foreground">Blood donations needed daily</p>
              </div>
              
              <div className="bg-white dark:bg-gray-900 rounded-xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm text-center">
                <div className="inline-flex items-center justify-center w-16 h-16 bg-blood/10 rounded-full mb-6">
                  <Clock className="h-8 w-8 text-blood" />
                </div>
                <h3 className="text-4xl font-bold mb-2">3</h3>
                <p className="text-muted-foreground">Lives saved with each donation</p>
              </div>
              
              <div className="bg-white dark:bg-gray-900 rounded-xl p-8 border border-gray-100 dark:border-gray-800 shadow-sm text-center">
                <div className="inline-flex items-center justify-center w-16 h-16 bg-blood/10 rounded-full mb-6">
                  <Users className="h-8 w-8 text-blood" />
                </div>
                <h3 className="text-4xl font-bold mb-2">1%</h3>
                <p className="text-muted-foreground">Of population who donate regularly</p>
              </div>
            </div>
          </div>
        </section>

        {/* Info Section */}
        <section className="px-6 py-16 bg-gray-50 dark:bg-gray-900">
          <div className="max-w-7xl mx-auto">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
              <div>
                <div className="inline-flex items-center gap-2 bg-ocean/10 text-ocean px-4 py-1.5 rounded-full text-sm font-medium mb-6">
                  <AlertCircle className="h-4 w-4" />
                  <span>Important Information</span>
                </div>
                <h2 className="text-3xl font-bold mb-6">
                  What you should know before donating
                </h2>
                <div className="space-y-6">
                  <div className="flex">
                    <div className="flex-shrink-0 mt-1">
                      <CheckCircle className="h-5 w-5 text-blood" />
                    </div>
                    <div className="ml-4">
                      <h3 className="text-lg font-semibold mb-2">Eat well and stay hydrated</h3>
                      <p className="text-muted-foreground">
                        Have a healthy meal and drink plenty of water before your donation. Avoid fatty foods and alcohol for 24 hours before donating.
                      </p>
                    </div>
                  </div>
                  
                  <div className="flex">
                    <div className="flex-shrink-0 mt-1">
                      <CheckCircle className="h-5 w-5 text-blood" />
                    </div>
                    <div className="ml-4">
                      <h3 className="text-lg font-semibold mb-2">Bring identification</h3>
                      <p className="text-muted-foreground">
                        You'll need to present a valid ID, such as your driver's license, passport, or donor card, at the donation center.
                      </p>
                    </div>
                  </div>
                  
                  <div className="flex">
                    <div className="flex-shrink-0 mt-1">
                      <CheckCircle className="h-5 w-5 text-blood" />
                    </div>
                    <div className="ml-4">
                      <h3 className="text-lg font-semibold mb-2">Know your medical history</h3>
                      <p className="text-muted-foreground">
                        Be prepared to answer questions about your health history, medications, and recent travel during the screening process.
                      </p>
                    </div>
                  </div>
                  
                  <div className="flex">
                    <div className="flex-shrink-0 mt-1">
                      <CheckCircle className="h-5 w-5 text-blood" />
                    </div>
                    <div className="ml-4">
                      <h3 className="text-lg font-semibold mb-2">Rest after donation</h3>
                      <p className="text-muted-foreground">
                        Plan to rest for at least 15 minutes after donating and avoid strenuous activity for 24 hours. Continue to drink plenty of fluids.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div className="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-md border border-gray-100 dark:border-gray-700">
                <h3 className="text-xl font-semibold mb-6">The Blood Donation Process</h3>
                <div className="space-y-8">
                  {bloodDonationProcess.map((step, index) => (
                    <div key={index} className="flex">
                      <div className="flex-shrink-0">
                        <div className="flex items-center justify-center w-10 h-10 rounded-full bg-blood/10 text-blood">
                          {step.icon}
                        </div>
                      </div>
                      <div className="ml-4">
                        <h4 className="text-lg font-medium mb-1">
                          {step.title}
                        </h4>
                        <p className="text-muted-foreground">
                          {step.description}
                        </p>
                        {index < bloodDonationProcess.length - 1 && (
                          <div className="h-12 w-px bg-gray-200 dark:bg-gray-700 ml-5 my-2"></div>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* FAQ Section */}
        <section className="px-6 py-16">
          <div className="max-w-4xl mx-auto">
            <div className="text-center mb-12">
              <h2 className="text-3xl font-bold mb-4">
                Frequently Asked Questions
              </h2>
              <p className="text-muted-foreground">
                Find answers to common questions about blood donation.
              </p>
            </div>

            <div className="space-y-6">
              {faqItems.map((faq, index) => (
                <div 
                  key={index}
                  className="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-800"
                >
                  <h3 className="text-lg font-semibold mb-3">{faq.question}</h3>
                  <p className="text-muted-foreground">{faq.answer}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="px-6 py-16 bg-blood text-white">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="text-3xl font-bold mb-4">
              Ready to make a difference?
            </h2>
            <p className="text-white/80 mb-8 text-lg">
              Your donation can help save lives. Register now to become a donor or find a donor near you.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <a 
                href="/register"
                className="px-8 py-3 bg-white text-blood rounded-full font-medium hover:bg-gray-100 transition-colors shadow-md"
              >
                Become a Donor
              </a>
              <a 
                href="/find-donor"
                className="px-8 py-3 bg-transparent border border-white text-white rounded-full font-medium hover:bg-white/10 transition-colors"
              >
                Find a Donor
              </a>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
};

export default About;
