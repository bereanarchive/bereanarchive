/**
 * If this file is included, it adds a title attribute with a definition for any word inside <dfn></dfn> tags*/

// Declare terms
var definitions = {
	antisense: "The opposite side of a double-helix strand of DNA.",
	'amino acid':  "Amino acids are small molecules used as building blocks for proteins.",
	bind: "One molecule latching onto another, usually by matching specific complementary shapes and forces on their surfaces.",
	cerebellum:  "Part of the brain at the back of the skull that coordinates and regulates muscular activity.",
	chromatin:  "Chromatin is the material chromosomes are made of.  Proteins, DNA, and RNA.",	
	coding: "Coding DNA provides instructions for assembling proteins.",
	conserved:  "DNA that is the same in distantly related organisms.  Under evolutionary theory this indicates function, because otherwise evolution would not have kept them the same.",
	'constructive mutation':  "A \"constructive mutation\" is defined here as a gain or modification of function that is beneficial.  This is distinguished from loss of function mutations that are beneficial.",
	cortex:  "The outer layer of the cerebrum (front part of the brain) that is involved in consciousness",
	cytoplasm:  "The cytoplasm is the part inside of a cell that is not part of the nucleus (center).",
	deleterious: "In medical science:  A mutation that is harmful.  Or in population genetics: a mutation that reduces the number of offspring an organism produces.",
	'dentate gyrus': "Part of the hippocampus (which is part of the brain) thought to be involved in the development of new memories.",
	differentiation: "The process where a less specialized cell becomes a more specialized cell.  For example fertalized egg cell dividing and becoming other cell types.",
	diploid: "Two sets of chromsomes, or an organism that has two sets of chromosomes.",
	encode: "ENCODE is an ongoing project by the United States National Institute of Health (NIH) to find function of the various sequences of human DNA.  The project involves hundreds of scientists and hundreds of millions of dollars in funding.",
	epigenome: "Information in a cell outside of the DNA that tells the DNA when and how to be used.",	
	eukaryote: "A ukaryote is organism with cells that have a nucleus--an enclosed center region that contains the DNA.  Animals, plants, and fungi are some examples of eukaryotes.  Bacteria and archaea are not eukaryotes.",
	express: "Expression is the process of activating and using a gene.",
	fitness: "The ability of an organism to survive and reproduce.",
	fix: "A mutation becomes fixed after a number of generations when it spreads to every surviving member of a population.",
	'genetic diversity': "In a population, different members have different mutations.  Genetic diversity is a measure of the number of differences present in a population.",
	'genetic load':  "In simplified terms, genetic load is a measure of the average number of deleterious mutations in a population.",
	genome: "A genome is all of the genetic material in one of an organism's cells, or in a virus.",
	ftir: "Fourier-transform infrared spectroscopy: A technique to measure individual colors beyond what the human eye can see.",
	haploid: "A single set of unpaired chromosomes, or an organism that has only one set of chromosomes.",
	hippocampus:  "Part of the brain thought to be the center of emotion, memory, and the autonomic nervous system",
	'intelligent design': "The idea that some features of the universe and of living things are best explained by an intelligent cause and not undirected processes.",
	intergenic: "Areas of DNA between protein coding genes.  Protein coding genes are DNA sequences with instructions for assembling proteins.",
	lincrna: "Long intergenic noncoding RNAs:  A longer RNA that does not go to a ribosome to become a protein, but instead does other functions.",
	mutation: "A change in an organism's genetic material (DNA, or RNA for some viruses).",
	'molecular clock': 'Molecular clocks estimate time by counting how many nucleotide (letters) are different between the genetic material of two organisms, and estimating how long it would take for mutations to create those differences.',
	'nucleic acid':  "A string of nucleotides.  DNA and RNA are nucleic acids.",
	nucleotide: "A nucleotide is a molecule that acts as a \"letter\" of DNA or RNA",
	noncoding: "Non-coding is DNA that does not directly provide instructions for assembling proteins.",
	pathogenic: "Causing disease.",
	'non-pathogenic': "Not causing disease.",
	phenotype: "A phenotype is an organism's observable characteristics.",
	'point mutation': "A point mutation changes onen nucleotide  \"letter\" of DNA to another.",
	polymorphic: "A nucleotide (letter) of genetic material that's different between two organisms.",
	promoter: "A section of DNA where a protein latches on and begins copying it to RNA.",
	protein: "A protein is a molecular machine made in cells by assembling amino acids.",
	'purkinje cells': "Neurons in the cerebellar cortex of the brain.",
	'reproductive rate': "The average number of offspring an organism has.",
	provirus: 'The code of a virus integrated into a host cell.',
	retrovirus: 'An RNA virus that inserts its genome into a host cell as DNA.',
	ribosome: "A ribosome is a factory inside a cell that reads the instructions in RNA to produce a protein.",
	rna: "In most organisms, RNA is a molecule that stores a copy of the information in DNA to send elsewhere in a cell, and can also perform functions of its own.",
	'rna polymerase': "RNA polymerases are protein machines that copy DNA to RNA.",
	stochastic: "Stochastic means randomly determined.",
	substitution: "A substitution is a mutation that changes one nucleotide \"letter\" of DNA to another.",
	'tof-sims': "Time-of-flight secondary ion mass spectrometry:  A technique that can be used to detect the chemical makeup of the surface of a sample.",
	transcribe: "The process of copying DNA into RNA.",
	transcript: "RNA that was produced by being copied from DNA.",
	translate: "Translation happens when a ribosome in a cell reads the instructions on RNA to produce a protein.",
	transposon:  "A transposon (also called jumping genes, mobile elements, or transposable lements) is a piece of DNA that can move or copy itself to different places in the genome.",
	'virion': "A complete virus that exists outside of a cell."
}

// Declare synonyms
//definitions['amino acid'] = definitions['amino acids']
//definitions.binding = definitions.bind;
definitions.constructive = definitions['constructive mutation']
definitions['deleterious mutation'] = definitions.deleterious
//definitions['deleterious mutations'] = definitions.deleterious
definitions.eukaryotic = definitions.eukaryote;
//definitions.eukaryotes = definitions.eukaryote;
//definitions.expressed = definitions.express;
definitions.fixation = definitions.fix;
//definitions.genomes = definitions.genome;
definitions.id = definitions['intelligent design'];
//definitions.lincrnas = definitions.lincrna;
//definitions.nucleotides = definitions.nucleotide;
//definitions['nucleic acids'] = definitions['nucleic acid'];
//definitions.proteins = definitions.protein;
definitions['protein coding'] = definitions.coding;
//definitions.promoters = definitions.promoter;
definitions.phenotypic = definitions.phenotype;
//definitions['reproductive rates'] = definitions['reproductive rate']
//definitions.ribosomes = definitions.ribosome;
//definitions.substitutions = definitions.substitution;
definitions.transcribed = definitions.transcribe;
definitions.transcription = definitions.transcribe;
definitions.transcriptional = definitions.transcribe;
definitions.translated = definitions.translate;
definitions['transposable element'] = definitions.transposon;
definitions['te'] = definitions.transposon;
definitions['jumping gene'] = definitions.transposon;
definitions['mobile element'] = definitions.transposon;

function stem(word) {
	return word.replace(/(s|ed|ing|ion)$/, '');
}

// Apply the tooltips
$('dfn').each(function(i, el) {
	var term = el.innerHTML.toLowerCase();
	term = term.replace(/\s+/g, ' ').trim();
	var stemmed = stem(term);
	var def = definitions[term] || definitions[stemmed];
	if (def)
		el.setAttribute('title', def)
	else {
		el.parentNode.insertBefore(el.firstChild, el);
		el.parentNode.removeChild(el);
		console.log('No defintion for ' + term);
	}
});
