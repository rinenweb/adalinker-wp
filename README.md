# ADALinker for WordPress

Το **ADALinker** είναι ένα plugin για το WordPress που αναζητά στο περιεχόμενο του ιστοτόπου αναφορές τύπου `ΑΔΑ:` και μετατρέπει αυτόματα τον αριθμό της πράξης που ακολουθεί σε σύνδεσμο προς τη σελίδα ή το αρχείο της αντίστοιχης πράξης στη Δι@ύγεια.

Παράδειγμα:

```text
ΑΔΑ: Ψ123Ω-ABC
```

μπορεί να μετατραπεί αυτόματα σε σύνδεσμο προς:

```text
https://diavgeia.gov.gr/decision/view/Ψ123Ω-ABC
```

ή, ανάλογα με τη ρύθμιση του plugin, προς:

```text
https://diavgeia.gov.gr/doc/Ψ123Ω-ABC?inline=true
```

## Απαιτήσεις

Το plugin είναι απλό στην υλοποίηση και έχει σχεδιαστεί για:

- WordPress 5.8+
- PHP 7.4+

## Εγκατάσταση

1. Κατεβάστε το αρχείο ZIP του plugin από το repository ή από τα releases.
2. Στο WordPress admin πηγαίνετε στο **Plugins → Add New → Upload Plugin**.
3. Ανεβάστε το ZIP και πατήστε **Install Now**.
4. Μετά την εγκατάσταση, ενεργοποιήστε το plugin από τη σελίδα **Plugins**.
5. Προαιρετικά, πηγαίνετε στο **Settings → ADALinker** για να αλλάξετε τις ρυθμίσεις του plugin.

## Λειτουργία

Το ADALinker εφαρμόζεται στο περιεχόμενο των δημοσιεύσεων και των αποσπασμάτων μέσω των WordPress filters `the_content` και `the_excerpt`.

Αναζητά τη λέξη-κλειδί:

```text
ΑΔΑ:
```

και διαβάζει τον αριθμό της πράξης που ακολουθεί, για παράδειγμα:

```text
ΑΔΑ: Α1Β2Γ3Δ4-Ε5Ζ
```

Στη συνέχεια δημιουργεί έναν σύνδεσμο προς τη Δι@ύγεια. Το αρχικό περιεχόμενο δεν τροποποιείται στη βάση δεδομένων· η μετατροπή γίνεται μόνο κατά την εμφάνιση της σελίδας.

Το plugin αγνοεί αναφορές ΑΔΑ που βρίσκονται ήδη μέσα σε υπάρχοντες συνδέσμους, ώστε να αποφεύγεται η δημιουργία nested links.

## Ρυθμίσεις

Οι ρυθμίσεις βρίσκονται στο WordPress admin, στη διαδρομή:

```text
Settings → ADALinker
```

Διατίθενται οι εξής επιλογές:

### Κλάση συνδέσμου

Προαιρετική CSS κλάση που προστίθεται στους συνδέσμους που δημιουργεί το plugin.

Παράδειγμα:

```text
ada-link
```

Έτσι μπορείτε να μορφοποιήσετε τους συνδέσμους με CSS ή να τους χρησιμοποιήσετε με άλλα frontend scripts.

### Τύπος συνδέσμου

Ορίζει πού θα οδηγεί ο σύνδεσμος:

- **Προβολή σελίδας στη Δι@ύγεια**  
  Δημιουργεί σύνδεσμο της μορφής:

  ```text
  https://diavgeia.gov.gr/decision/view/ΑΔΑ
  ```

- **Προβολή αρχείου**  
  Δημιουργεί σύνδεσμο της μορφής:

  ```text
  https://diavgeia.gov.gr/doc/ΑΔΑ?inline=true
  ```

## Παραδείγματα

Αρχικό κείμενο:

```text
Η απόφαση δημοσιεύθηκε με ΑΔΑ: Α1Β2Γ3Δ4-Ε5Ζ.
```

Εμφάνιση μετά την ενεργοποίηση του plugin:

```html
Η απόφαση δημοσιεύθηκε με ΑΔΑ: <a href="https://diavgeia.gov.gr/decision/view/Α1Β2Γ3Δ4-Ε5Ζ" target="_blank" rel="noopener noreferrer">Α1Β2Γ3Δ4-Ε5Ζ</a>.
```

Αν ο ΑΔΑ βρίσκεται ήδη μέσα σε σύνδεσμο, το plugin δεν τον μετατρέπει ξανά.

## Τεχνικές σημειώσεις

- Υποστηρίζει ελληνικούς και λατινικούς χαρακτήρες στον αριθμό ΑΔΑ.
- Χρησιμοποιεί Unicode-safe regular expression.
- Αποφεύγει τη δημιουργία nested `<a>` tags.
- Χρησιμοποιεί escaping για URL, attributes και εμφανιζόμενο κείμενο.
- Προσθέτει `rel="noopener noreferrer"` σε links που ανοίγουν με `target="_blank"`.
- Δεν αλλάζει το αποθηκευμένο περιεχόμενο των δημοσιεύσεων.

## Άδεια χρήσης και αποποίηση ευθύνης

Αυτό το πρόγραμμα είναι ελεύθερο λογισμικό και διανέμεται υπό τους όρους της άδειας χρήσης GNU GPL v3 ή νεότερης.

Το πρόγραμμα διανέμεται με την ελπίδα ότι θα είναι χρήσιμο, αλλά **χωρίς καμία εγγύηση**. Δεν παρέχεται εγγύηση εμπορευσιμότητας ή καταλληλότητας για συγκεκριμένο σκοπό.

Δεν προσφέρεται κανενός είδους εγγυημένη υποστήριξη. Η χρήση ελεύθερα διανεμόμενου λογισμικού δεν δημιουργεί υποχρέωση δωρεάν υποστήριξης ή εργασίας από τους δημιουργούς του.

---

# ADALinker for WordPress — English Description

**ADALinker** is a WordPress plugin that scans website content for Greek `ΑΔΑ:` references and automatically converts the following decision code into a link to the corresponding decision page or document on Diavgeia.

Example:

```text
ΑΔΑ: Α1Β2Γ3Δ4-Ε5Ζ
```

can be automatically converted into a link to:

```text
https://diavgeia.gov.gr/decision/view/Α1Β2Γ3Δ4-Ε5Ζ
```

or, depending on the plugin settings, to:

```text
https://diavgeia.gov.gr/doc/Α1Β2Γ3Δ4-Ε5Ζ?inline=true
```

## Requirements

- WordPress 5.8+
- PHP 7.4+

## Installation

1. Download the plugin ZIP file from this repository or from the releases page.
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP file and click **Install Now**.
4. Activate the plugin from the **Plugins** page.
5. Optionally, go to **Settings → ADALinker** to configure the plugin.

## Usage

ADALinker runs on post content and excerpts using the WordPress `the_content` and `the_excerpt` filters.

It searches for the keyword:

```text
ΑΔΑ:
```

followed by a decision code, such as:

```text
ΑΔΑ: Α1Β2Γ3Δ4-Ε5Ζ
```

The plugin then creates a link to Diavgeia. The original post content is not modified in the database; the conversion happens only when the content is displayed.

Already linked ADA references are ignored, preventing nested links.

## Settings

The plugin settings are available under:

```text
Settings → ADALinker
```

Available options:

### Link Class

An optional CSS class added to the generated links.

Example:

```text
ada-link
```

### Link Type

Defines where the generated link points to:

- **Decision page on Diavgeia**

  ```text
  https://diavgeia.gov.gr/decision/view/ADA
  ```

- **Decision document**

  ```text
  https://diavgeia.gov.gr/doc/ADA?inline=true
  ```

## Technical Notes

- Supports Greek and Latin characters in ADA codes.
- Uses a Unicode-safe regular expression.
- Avoids nested `<a>` tags.
- Escapes URLs, HTML attributes and visible text.
- Adds `rel="noopener noreferrer"` to links opened with `target="_blank"`.
- Does not modify saved post content.

## License and Disclaimer

This program is free software and is distributed under the terms of the GNU General Public License v3 or later.

This program is distributed in the hope that it will be useful, but **without any warranty**; without even the implied warranty of merchantability or fitness for a particular purpose.

No guaranteed support is provided. Using freely distributed software does not entitle users to free support or labor from the developers.
